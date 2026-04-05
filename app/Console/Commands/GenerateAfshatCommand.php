<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Setup;
use App\Models\Punchline;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Str;

class GenerateAfshatCommand extends Command
{
    protected $signature = 'app:generate-afshat {count=5}';

    protected $description = 'Generate new Egyptian afshat using Gemini AI';

    public function handle()
    {
        $count = $this->argument('count');
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            $this->error('GEMINI_API_KEY is not set in .env');
            return 1;
        }

        $apiKey = trim($apiKey, " \t\n\r\0\x0B\"'");

        try {
            $listResponse = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
            if ($listResponse->successful()) {
                $modelsData = $listResponse->json();
                $availableModels = collect($modelsData['models'] ?? [])->pluck('name')->toArray();
            }
        } catch (\Exception $e) {}

        $this->info("Generating {$count} Egyptian afshat with 10 replies each using Gemini...");

        $prompt = "أنت شاب مصري دمه خفيف جداً وصانع محتوى كوميدي. 
        اكتب {$count} مواقف (Setup) قصيرة من سطر واحد.
        لكل موقف، اكتب مصفوفة من 10 ردود (Punchlines) قوية ومختلفة وقصف جبهة باللهجة المصرية.
        
        رجع النتيجة بصيغة JSON حصراً (مصفوفة من الأشياء):
        - setup: الموقف.
        - punchline: مصفوفة [] من 10 ردود ساخرة.
        - tags: مصفوفة من تاجات مناسبة (مثل: #شغل، #صحاب).
        - comments: مصفوفة من 5 تعليقات مصرية ساخرة ليتم توزيعها.
        رجع JSON فقط وبدون علامات تنصيص عربية.";

        $models = [
            'gemini-2.0-flash',
            'gemini-flash-latest',
        ];
        $version = 'v1beta'; 

        try {
            $response = null;
            $success = false;

            foreach ($models as $model) {
                $this->info("Trying model: {$model}...");
                $response = Http::timeout(120)->post("https://generativelanguage.googleapis.com/{$version}/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

                if ($response->successful()) {
                    $success = true;
                    $this->info("SUCCESS! Using model: {$model}");
                    break;
                } else {
                    $error = $response->json()['error']['message'] ?? 'Unknown Error';
                    $this->warn("Model {$model} failed: {$error}");
                }
            }

            if (!$success) {
                $this->error("All attempts failed.");
                return 1;
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
            
            $text = preg_replace('/^```json\s*|\s*```$/i', '', trim($text));
            $text = str_replace('،', ',', $text); 
            $afshatData = json_decode($text, true);

            if (!is_array($afshatData)) {
                $this->error('Logic Error: Invalid JSON received.');
                return 1;
            }

            $botUsers = User::where('is_bot', 1)->orWhere('email', 'like', '%@afchat.fun')->get();
            if ($botUsers->isEmpty()) {
                $botUsers = collect([User::first()]);
            }

            foreach ($afshatData as $item) {
                $setupUser = $botUsers->random();
                $setupText = trim($item['setup'] ?? 'موقف بدون نص', " \"'");

                $setup = Setup::create([
                    'user_id' => $setupUser->id,
                    'text' => $setupText,
                    'media_type' => 'text',
                ]);

                $punchlinesArr = (array) ($item['punchline'] ?? []);
                if (empty($punchlinesArr) && !empty($item['punchline'])) {
                    $punchlinesArr = [$item['punchline']];
                }

                foreach ($punchlinesArr as $pText) {
                    $punchline = Punchline::create([
                        'setup_id' => $setup->id,
                        'user_id' => $botUsers->random()->id,
                        'text' => $pText,
                        'media_type' => 'text',
                        'laughs' => rand(5, 50),
                        'views' => rand(100, 500),
                    ]);

                    if (isset($item['comments']) && is_array($item['comments'])) {
                        foreach ($item['comments'] as $cBody) {
                            \App\Models\Comment::create([
                                'user_id' => $botUsers->random()->id,
                                'punchline_id' => $punchline->id,
                                'body' => $cBody,
                            ]);
                        }
                    }
                }

                if (isset($item['tags']) && is_array($item['tags'])) {
                    $tagIds = [];
                    foreach ($item['tags'] as $tagName) {
                        $tag = Tag::firstOrCreate(['name' => trim($tagName, '# ')]);
                        $tagIds[] = $tag->id;
                    }
                    $setup->tags()->sync($tagIds);
                }

                $this->line("Created Setup with " . count($punchlinesArr) . " replies: " . Str::limit($setup->text, 30));
            }

            $this->info("Generation successful!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Critical Error: " . $e->getMessage());
            return 1;
        }
    }
}
