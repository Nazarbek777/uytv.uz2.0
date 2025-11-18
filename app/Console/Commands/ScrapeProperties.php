<?php

namespace App\Console\Commands;

use App\Services\PropertyScraperService;
use Illuminate\Console\Command;

class ScrapeProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:scrape {--limit=100 : Nechta uy-joy yig\'ish kerak}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turli saytlardan uy-joy ma\'lumotlarini yig\'ib database\'ga saqlash';

    /**
     * Execute the console command.
     */
    public function handle(PropertyScraperService $scraper)
    {
        $limit = (int) $this->option('limit');
        
        $this->info("🔄 Uy-joy ma'lumotlarini yig'ish boshlandi...");
        $this->info("Limit: {$limit} ta uy-joy");
        
        // OpenAI API key tekshirish
        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            $this->error("❌ OpenAI API key topilmadi! Iltimos, .env faylga OPENAI_API_KEY qo'shing.");
            $this->info("Yoki admin panel orqali: /admin/settings");
            return 1;
        }
        
        $this->info("✅ OpenAI API key topildi: " . substr($apiKey, 0, 20) . "...");
        
                // Ma'lumotlarni yig'ish
                $this->info("📡 Telegram kanallardan ma'lumot yig'ilmoqda...");
                $properties = $scraper->scrapeProperties($limit);
        
        $this->info("✅ " . count($properties) . " ta uy-joy topildi");
        
        if (count($properties) == 0) {
            $this->warn("⚠️  Hech qanday uy-joy topilmadi. Log faylni tekshiring: storage/logs/laravel.log");
            $this->info("Ehtimol, muammolar:");
            $this->line("  - OpenAI API key noto'g'ri");
            $this->line("  - Saytlar blok qilgan bo'lishi mumkin");
            $this->line("  - Internet aloqasi muammosi");
            return 1;
        }
        
        // Database'ga saqlash
        $this->info("💾 Database'ga saqlanmoqda...");
        $saved = $scraper->saveProperties($properties);
        
        $this->info("✅ {$saved} ta uy-joy muvaffaqiyatli saqlandi!");
        
        if ($saved < count($properties)) {
            $this->warn("⚠️  " . (count($properties) - $saved) . " ta uy-joy saqlanmadi (duplicate yoki xatolik)");
        }
        
        return 0;
    }
}
