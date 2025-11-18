<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin foydalanuvchisini yaratish
        $admin = User::firstOrCreate(
            ['email' => 'admin@uytv.uz'],
            [
                'name' => 'Admin',
                'email' => 'admin@uytv.uz',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'verified' => true,
                'featured' => true,
                'email_verified_at' => now(),
            ]
        );

        // Agar admin allaqachon mavjud bo'lsa, ma'lumotlarini yangilash
        if ($admin->wasRecentlyCreated) {
            $this->command->info('Admin foydalanuvchi muvaffaqiyatli yaratildi!');
            $this->command->info('Email: admin@uytv.uz');
            $this->command->info('Parol: admin123');
        } else {
            // Parolni yangilash (agar o'zgartirish kerak bo'lsa)
            $admin->update([
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'verified' => true,
                'featured' => true,
            ]);
            $this->command->info('Admin foydalanuvchi ma\'lumotlari yangilandi!');
            $this->command->info('Email: admin@uytv.uz');
            $this->command->info('Parol: admin123');
        }

        // Qo'shimcha adminlar (ixtiyoriy)
        $additionalAdmins = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@uytv.uz',
                'password' => Hash::make('superadmin123'),
                'role' => 'admin',
                'verified' => true,
                'featured' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($additionalAdmins as $adminData) {
            $admin = User::firstOrCreate(
                ['email' => $adminData['email']],
                $adminData
            );

            if ($admin->wasRecentlyCreated) {
                $this->command->info("Admin yaratildi: {$adminData['email']}");
            }
        }

        // Test provider yaratish (ixtiyoriy)
        $provider = User::firstOrCreate(
            ['email' => 'provider@uytv.uz'],
            [
                'name' => 'Test Provider',
                'email' => 'provider@uytv.uz',
                'password' => Hash::make('provider123'),
                'role' => 'provider',
                'verified' => true,
                'featured' => false,
                'phone' => '+998901234567',
                'company_name' => 'Test Real Estate Agency',
                'email_verified_at' => now(),
            ]
        );

        if ($provider->wasRecentlyCreated) {
            $this->command->info('Test Provider yaratildi!');
            $this->command->info('Email: provider@uytv.uz');
            $this->command->info('Parol: provider123');
        }
    }
}






