<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => '田中 太郎',
                'email' => 'tanaka@example.com',
                'password' => Hash::make('password123'),
                'profile_image' => 'profile_images/tanaka.jpg',
                'postal_code' => '100-0001',
                'address' => '東京都千代田区千代田1-1',
                'building' => '皇居前ビル101',
            ],
            [
                'name' => '佐藤 花子',
                'email' => 'sato@example.com',
                'password' => Hash::make('password123'),
                'profile_image' => 'profile_images/sato.jpg',
                'postal_code' => '150-0001',
                'address' => '東京都渋谷区神宮前1-1-1',
                'building' => '渋谷ビル203',
            ],
            [
                'name' => '鈴木 一郎',
                'email' => 'suzuki@example.com',
                'password' => Hash::make('password123'),
                'profile_image' => 'profile_images/suzuki.jpg',
                'postal_code' => '530-0001',
                'address' => '大阪府大阪市北区梅田1-1-1',
                'building' => '梅田タワー901',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
