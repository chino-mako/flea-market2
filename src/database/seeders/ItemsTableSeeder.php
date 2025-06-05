<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'title' => '腕時計',
                'brand_name' => 'Armani',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'user_id' => 1,
            ],
            [
                'title' => 'HDD',
                'brand_name' => 'Western Digital',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'user_id' => 1,
            ],
            [
                'title' => '玉ねぎ3束',
                'brand_name' => '北海道産',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'user_id' => 1,
            ],
            [
                'title' => '革靴',
                'brand_name' => 'REGAL',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'user_id' => 1,
            ],
            [
                'title' => 'ノートPC',
                'brand_name' => 'Dell',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition' => '良好',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'user_id' => 1,
            ],
            [
                'title' => 'マイク',
                'brand_name' => 'Audio-Technica',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'user_id' => 1,
            ],
            [
                'title' => 'ショルダーバッグ',
                'brand_name' => 'UNIQLO',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'user_id' => 1,
            ],
            [
                'title' => 'タンブラー',
                'brand_name' => 'Thermos',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'user_id' => 1,
            ],
            [
                'title' => 'コーヒーミル',
                'brand_name' => 'Kalita',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'user_id' => 1,
            ],
            [
                'title' => 'メイクセット',
                'brand_name' => 'CANMAKE',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition' => '目立った傷や汚れなし',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'user_id' => 1,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
