<?php

namespace Database\Seeders;

use App\Models\TelegramChannel;
use Illuminate\Database\Seeder;

class TelegramChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            [
                'name' => 'Уй Бозор | News',
                'username' => 'UYBOZORI_TOSHKENT_UY_JOY',
                'description' => '207k+ obunachi. Uy-joy e’lonlari, reklama va yangiliklar. Admin: @MANAGER_UY_BOZOR.',
            ],
            [
                'name' => 'TOSHKENT UY BOZORI',
                'username' => 'toshkent_uy1',
                'description' => 'Toshkent uy bozori e’lonlari. Creator: @HUSNlDDIN.',
            ],
        ];

        foreach ($channels as $channel) {
            TelegramChannel::updateOrCreate(
                ['username' => ltrim(strtolower($channel['username']), '@')],
                [
                    'name' => $channel['name'],
                    'description' => $channel['description'],
                    'is_active' => true,
                    'scrape_limit' => 50,
                    'scrape_days' => 7,
                ]
            );
        }
    }
}

