<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ScrapePropertiesJob;
use App\Models\TelegramChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    public function run(Request $request)
    {
        $request->validate([
            'channel_ids' => 'nullable|array',
            'channel_ids.*' => 'exists:telegram_channels,id',
            'limit' => 'nullable|integer|min:1|max:1000',
            'days' => 'nullable|integer|min:1|max:365',
        ]);

        $channelIds = $request->input('channel_ids', []);
        $limit = $request->input('limit');
        $days = $request->input('days');

        // Agar kanal tanlanmagan bo'lsa, faol kanallarni tekshirish
        if (empty($channelIds)) {
            $channels = TelegramChannel::active()->get();
        } else {
            $channels = TelegramChannel::whereIn('id', $channelIds)->get();
        }

        if ($channels->isEmpty()) {
            return back()->with('error', 'Hech qanday kanal topilmadi. Iltimos, kamida bitta kanal qo\'shing.');
        }

        // Job'ni queue'ga yuborish (asinxron ishlash)
        ScrapePropertiesJob::dispatch($channelIds, $limit, $days);

        $channelNames = $channels->pluck('name')->implode(', ');
        
        return back()->with('success', "Scraper ishga tushirildi! Kanal(lar): {$channelNames}. Scraper fon rejimida ishlayapti, sayt bloklanmaydi.");
    }

    /**
     * Queue status'ni qaytarish
     */
    public function status()
    {
        try {
            // Queue'dagi job'lar soni
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();
            
            // Oxirgi yig'ilgan vaqt
            $lastScraped = TelegramChannel::whereNotNull('last_scraped_at')
                ->orderBy('last_scraped_at', 'desc')
                ->first();
            
            // Oxirgi 10 ta log entry
            $recentLogs = $this->getRecentLogs('ScrapePropertiesJob', 10);

            return response()->json([
                'success' => true,
                'queue' => [
                    'pending' => $pendingJobs,
                    'failed' => $failedJobs,
                    'status' => $pendingJobs > 0 ? 'processing' : 'idle',
                ],
                'last_scraped' => $lastScraped ? [
                    'channel' => $lastScraped->name,
                    'time' => $lastScraped->last_scraped_at->diffForHumans(),
                    'timestamp' => $lastScraped->last_scraped_at->toDateTimeString(),
                ] : null,
                'recent_logs' => $recentLogs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Log faylidan oxirgi log'larni olish
     */
    private function getRecentLogs(string $searchTerm, int $limit = 10): array
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (!file_exists($logFile)) {
            return $logs;
        }

        try {
            $lines = file($logFile);
            $recentLines = array_slice($lines, -500); // Oxirgi 500 qator
            
            foreach (array_reverse($recentLines) as $line) {
                if (stripos($line, $searchTerm) !== false || 
                    stripos($line, 'Scraper') !== false ||
                    stripos($line, 'PropertyScraperService') !== false) {
                    $logs[] = trim($line);
                    if (count($logs) >= $limit) {
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            // Xatolik bo'lsa, bo'sh qaytarish
        }

        return array_reverse($logs);
    }
}
