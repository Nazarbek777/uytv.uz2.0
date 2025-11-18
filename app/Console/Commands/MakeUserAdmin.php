<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Foydalanuvchini admin qilish';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Foydalanuvchi topilmadi: {$email}");
            return 1;
        }
        
        $user->update([
            'role' => 'admin',
            'verified' => true,
            'featured' => true,
        ]);
        
        $this->info("✅ Foydalanuvchi admin qilindi!");
        $this->info("Email: {$user->email}");
        $this->info("Ism: {$user->name}");
        $this->info("Role: {$user->role}");
        
        return 0;
    }
}
