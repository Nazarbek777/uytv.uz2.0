<?php

namespace App\Services;

use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Api as TelegramApi;

class PropertyScraperService
{
    protected ?TelegramApi $telegram = null;
    protected ?string $openAiKey;
    protected string $openAiModel;
    protected array $channels;
    protected ?int $defaultProviderId = null;

    public function __construct()
    {
        $this->openAiKey = env('OPENAI_API_KEY');
        $this->openAiModel = env('OPENAI_MODEL', 'gpt-4o-mini');
        $this->channels = collect(explode(',', (string) env('SCRAPER_TELEGRAM_CHANNELS', '')))
            ->map(fn ($channel) => Str::lower(ltrim(trim($channel), '@')))
            ->filter()
            ->values()
            ->all();

        $token = env('TELEGRAM_BOT_TOKEN');
        if ($token) {
            $this->telegram = new TelegramApi($token);
        }
    }

    /**
     * Telegram postlardan property ma'lumotlarini yig'ish.
     */
    public function scrapeProperties(int $limit = 100): array
    {
        if (!$this->openAiKey) {
            Log::warning('PropertyScraperService: OPENAI_API_KEY topilmadi.');
            return [];
        }

        $posts = $this->fetchTelegramPosts($limit);
        $results = [];

        foreach ($posts as $post) {
            if (count($results) >= $limit) {
                break;
            }

            $parsed = $this->parsePostWithOpenAI($post);
            if ($parsed) {
                $results[] = $parsed;
            }
        }

        return $results;
    }

    /**
     * Tayyorlangan property massivini database'ga saqlash.
     */
    public function saveProperties(array $properties): int
    {
        $saved = 0;

        foreach ($properties as $payload) {
            if (empty($payload['title']) || empty($payload['price']) || empty($payload['source_id'])) {
                continue;
            }

            $property = Property::firstOrNew([
                'source' => $payload['source'] ?? 'telegram',
                'source_id' => $payload['source_id'],
            ]);

            if ($property->exists && $property->status === 'published') {
                continue;
            }

            $property->fill([
                'user_id' => $payload['user_id'] ?? $this->resolveDefaultProviderId(),
                'price' => $payload['price'],
                'currency' => $payload['currency'] ?? 'USD',
                'area' => $payload['area'] ?? null,
                'property_type' => $payload['property_type'] ?? 'apartment',
                'listing_type' => $payload['listing_type'] ?? 'sale',
                'city' => $payload['city'] ?? null,
                'region' => $payload['region'] ?? null,
                'status' => $payload['status'] ?? 'pending',
                'source' => $payload['source'] ?? 'telegram',
                'source_url' => $payload['source_url'] ?? null,
                'images' => $payload['images'] ?? [],
                'featured_image' => $payload['featured_image'] ?? null,
            ]);

            $property->save();

            foreach (['uz', 'ru', 'en'] as $locale) {
                $translationPayload = $payload['translations'][$locale] ?? $payload['translations']['uz'] ?? null;
                if (!$translationPayload) {
                    continue;
                }

                $translation = $property->translateOrNew($locale);
                $translation->title = $translationPayload['title'] ?? $payload['title'];
                $translation->description = $translationPayload['description'] ?? $payload['description'] ?? null;
                $translation->address = $translationPayload['address'] ?? $payload['address'] ?? null;
                $translation->save();
            }
            $saved++;
        }

        return $saved;
    }

    /**
     * Telegram kanallaridan xabarlarni olish.
     */
    protected function fetchTelegramPosts(int $limit): array
    {
        if (!$this->telegram) {
            Log::warning('PropertyScraperService: TELEGRAM_BOT_TOKEN topilmadi.');
            return [];
        }

        $offset = Cache::get('scraper:telegram_offset');
        $params = [
            'limit' => min($limit * 3, 100),
            'timeout' => 0,
        ];

        if ($offset) {
            $params['offset'] = $offset + 1;
        }

        $updates = $this->telegram->getUpdates($params);
        $posts = [];
        $lastUpdate = $offset;

        foreach ($updates as $update) {
            $lastUpdate = max($lastUpdate ?? 0, $update->updateId ?? 0);
            $channelPost = $update->channelPost ?? $update->message ?? null;

            if (!$channelPost) {
                continue;
            }

            $chat = $channelPost->chat;
            $username = Str::lower(ltrim($chat->username ?? (string) $chat->id, '@'));

            if (!empty($this->channels) && !in_array($username, $this->channels, true)) {
                continue;
            }

            $text = $channelPost->text ?? $channelPost->caption ?? null;
            if (!$text) {
                continue;
            }

            $photos = collect($channelPost->photo ?? [])
                ->map(function ($photo) {
                    if (is_object($photo) && method_exists($photo, 'toArray')) {
                        return $photo->toArray();
                    }
                    return (array) $photo;
                })
                ->toArray();

            $posts[] = [
                'message_id' => $channelPost->messageId,
                'channel' => $username,
                'chat_id' => $chat->id,
                'text' => $text,
                'photos' => $photos,
            ];
        }

        if ($lastUpdate) {
            Cache::put('scraper:telegram_offset', $lastUpdate, now()->addHours(2));
        }

        return array_slice($posts, 0, $limit);
    }

    /**
     * OpenAI yordamida xabarni struktur ma'lumotga aylantirish.
     */
    protected function parsePostWithOpenAI(array $post): ?array
    {
        try {
            $client = OpenAI::client($this->openAiKey);

            $response = $client->chat()->create([
                'model' => $this->openAiModel,
                'temperature' => 0.2,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Siz tajribali ko\'chmas mulk agentisiz. Matndan uy-joy ma\'lumotlarini JSON formatida ajrating. Har doim JSON qaytaring.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Matn:\n<<<\n{$post['text']}\n>>>\n\nNatija JSON:\n{\n  \"title\": \"\",\n  \"description\": \"\",\n  \"address\": \"\",\n  \"city\": \"\",\n  \"price\": 0,\n  \"currency\": \"USD\",\n  \"area\": 0,\n  \"bedrooms\": 0,\n  \"listing_type\": \"sale|rent\",\n  \"property_type\": \"apartment|house|villa|land|commercial\",\n  \"translations\": {\n     \"uz\": {\"title\": \"\", \"description\": \"\", \"address\": \"\"},\n     \"ru\": {...},\n     \"en\": {...}\n  }\n}",
                    ],
                ],
                'max_tokens' => 800,
            ]);

            $content = $response->choices[0]->message->content ?? '';
            $parsed = $this->extractJson($content);

            if (!$parsed) {
                return null;
            }

            return [
                'title' => $parsed['title'] ?? null,
                'description' => $parsed['description'] ?? null,
                'address' => $parsed['address'] ?? null,
                'city' => $parsed['city'] ?? null,
                'price' => isset($parsed['price']) ? (float) $parsed['price'] : null,
                'currency' => $parsed['currency'] ?? 'USD',
                'area' => isset($parsed['area']) ? (float) $parsed['area'] : null,
                'bedrooms' => $parsed['bedrooms'] ?? null,
                'listing_type' => $parsed['listing_type'] ?? 'sale',
                'property_type' => $parsed['property_type'] ?? 'apartment',
                'status' => 'pending',
                'source' => 'telegram',
                'source_id' => (string) $post['message_id'],
                'source_url' => $this->buildTelegramUrl($post['channel'], $post['message_id']),
                'images' => $this->resolvePhotoUrls($post['photos']),
                'translations' => $parsed['translations'] ?? [],
            ];
        } catch (\Throwable $exception) {
            Log::warning('PropertyScraperService: OpenAI parsing xatosi', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Telegram foto fayllarini URL ga o'girish.
     */
    protected function resolvePhotoUrls(array $photos): array
    {
        if (!$this->telegram || empty($photos)) {
            return [];
        }

        $token = env('TELEGRAM_BOT_TOKEN');
        $urls = [];

        foreach ($photos as $photo) {
            try {
                $file = $this->telegram->getFile(['file_id' => $photo['file_id'] ?? null]);
                if ($file && $file->filePath) {
                    $urls[] = "https://api.telegram.org/file/bot{$token}/{$file->filePath}";
                }
            } catch (\Throwable $exception) {
                Log::debug('PropertyScraperService: telegram file olinmadi', [
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return array_values(array_unique($urls));
    }

    /**
     * Matndan JSON ni ajratib olish.
     */
    protected function extractJson(string|array $content): ?array
    {
        if (is_array($content)) {
            $content = collect($content)->pluck('text')->implode('');
        }

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Telegram post URL yaratish.
     */
    protected function buildTelegramUrl(string $channel, string $messageId): string
    {
        $channel = ltrim($channel, '@');
        return "https://t.me/{$channel}/{$messageId}";
    }

    /**
     * Default provider foydalanuvchisini aniqlash.
     */
    protected function resolveDefaultProviderId(): ?int
    {
        if ($this->defaultProviderId) {
            return $this->defaultProviderId;
        }

        $email = env('SCRAPER_PROVIDER_EMAIL');
        $user = null;

        if ($email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            $user = User::where('role', 'provider')->first();
        }

        if (!$user) {
            $user = User::first();
        }

        return $this->defaultProviderId = $user?->id;
    }
}
