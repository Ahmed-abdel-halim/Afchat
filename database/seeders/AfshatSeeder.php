<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Setup;
use App\Models\Punchline;


class AfshatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
 public function run(): void
    {
        $items = [
            [
                'text' => 'العميل: عندي تعديل بسيط جدًا...',
                'punchlines' => [
                    'التعديل: هنعيد كتابة السيستم من الصفر 😄',
                    'بسيط: هنغيّر كل الفلو + الداتابيز',
                    'بس “5 دقايق” = أسبوعين شغل',
                ],
            ],
            [
                'text' => 'واحد راح للدكتور وقاله تعبان...',
                'punchlines' => [
                    'قاله: سيب الموبايل… قاله: طب اشحنه؟',
                    'قاله: نام بدري… قاله: طب والمنبه؟',
                ],
            ],
        ];

        foreach ($items as $it) {
            $setup = Setup::create([
                'text' => $it['text'],
                'slug' => Str::slug(Str::limit($it['text'], 40, ''), '-').'-'.Str::random(5),
                'media_type' => 'text',
            ]);

            foreach ($it['punchlines'] as $p) {
                Punchline::create([
                    'setup_id' => $setup->id,
                    'text' => $p,
                    'media_type' => 'text',
                ]);
            }
        }
    }
}
