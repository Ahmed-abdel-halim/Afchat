<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FakeUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'محمود الكوميديان',
            'زيزو ابن البلد',
            'البرنس المصري',
            'أبو ضحكة جنان',
            'فنان قفشات',
            'سيد قصف جبهة',
            'حودة روقان',
            'ميمي المضحكاتي',
            'كابتن فرفشة',
            'ملك الأفشات'
        ];

        foreach ($names as $name) {
            User::firstOrCreate(
                ['email' => Str::slug($name) . '@afchat.fun'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_bot' => true, // Assuming we add this field or just leave it
                ]
            );
        }
    }
}
