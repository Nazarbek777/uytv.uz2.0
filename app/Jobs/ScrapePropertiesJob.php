<?php

namespace App\Jobs;

use App\Models\TelegramChannel;
use App\Services\PropertyScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScrapePropertiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 daqiqa
    public $tries = 3; // 3 marta urinish

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $channelIds,
        public ?int $limit = null,
        public ?int $days = null
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Agar kanal tanlanmagan bo'lsa, faol kanallarni olish
        if (empty($this->channelIds)) {
            $channels = TelegramChannel::active()->get();
        } else {
            $channels = TelegramChannel::whereIn('id', $this->channelIds)->get();
        }

        if ($channels->isEmpty()) {
            Log::warning('ScrapePropertiesJob: Hech qanday kanal topilmadi');
            return;
        }

        $scraper = new PropertyScraperService();
        $totalScraped = 0;
        $errors = [];

        foreach ($channels as $channel) {
            try {
                // Kanal uchun maxsus limit va kunlar
                $channelLimit = $this->limit ?? $channel->scrape_limit;
                $channelDays = $this->days ?? $channel->scrape_days;

                // Bot ishlatmasdan, faqat OpenAI orqali kanaldan ma'lumotlarni olish
                $channelLink = "https://t.me/{$channel->username}";
                $properties = $scraper->scrapeFromChannelLink($channelLink, $channelLimit);
                
                if (!empty($properties)) {
                    $saved = $scraper->saveProperties($properties);
                    $totalScraped += $saved;

                    // Kanal statistikasini yangilash
                    $channel->increment('total_scraped', $saved);
                    $channel->update(['last_scraped_at' => now()]);
                    
                    Log::info("ScrapePropertiesJob: {$channel->name} kanalidan {$saved} ta uy-joy yig'ildi");
                } else {
                    Log::info('ScrapePropertiesJob: Kanaldan hech qanday uy-joy topilmadi', [
                        'channel' => $channel->username,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('ScrapePropertiesJob xatosi kanal uchun: ' . $channel->name, [
                    'error' => $e->getMessage(),
                    'channel_id' => $channel->id,
                    'trace' => $e->getTraceAsString(),
                ]);
                $errors[] = $channel->name . ': ' . $e->getMessage();
            }
        }

        Log::info("ScrapePropertiesJob: Yakunlandi. Jami {$totalScraped} ta uy-joy yig'ildi.", [
            'errors' => $errors,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ScrapePropertiesJob: Job muvaffaqiyatsiz yakunlandi', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
