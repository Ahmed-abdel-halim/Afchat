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
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-afshat {count=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new Egyptian afshat using Gemini AI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            $this->error('GEMINI_API_KEY is not set in .env');
            return 1;
        }

        // AGGRESSIVE CLEANING: Removing quotes that often cause 404/Invalid Key errors in .env
        $apiKey = trim($apiKey, " \t\n\r\0\x0B\"'");

        $this->info("Diagnostic: Fetching available models for your API key...");
        
        try {
            $listResponse = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
            if ($listResponse->successful()) {
                $modelsData = $listResponse->json();
                $availableModels = collect($modelsData['models'] ?? [])->pluck('name')->toArray();
                $this->info("Found " . count($availableModels) . " models: " . implode(', ', $availableModels));
            } else {
                $this->error("Diagnostic Failed: Status " . $listResponse->status() . " - " . ($listResponse->json()['error']['message'] ?? 'Unknown Error'));
            }
        } catch (\Exception $e) {
            $this->warn("Model listing failed, continuing anyway...");
        }

        $this->info("Generating {$count} Egyptian afshat using Gemini (Diagnostic Mode)...");

        $prompt = "أنت شاب مصري دمه خفيف جداً. اكتب {$count} مواقف (Setup) قصيرة من سطر واحد، واكتب أقوى رد قصف جبهة عليه (Punchline) باللهجة المصرية الدارجة. 
        اختار موضوعات عشوائية (شغل، جواز، صحاب، مواصلات، أهل، خروجات). 
        رجع النتيجة بصيغة JSON حصراً عبارة عن مصفوفة (Array) من الأشياء، كل شيء فيه:
        - setup: الموقف المستفز أو الافتتاحي.
        - punchline: الرد السريع المفحم.
        - tags: مصفوفة من تاجات مناسبة (مثل: #شغل، #صحاب).
        - comments: مصفوفة من 3 تعليقات مصرية ساخرة على الرد (punchline) ده بالذات.
        رجع JSON فقط.";

        // Using models confirmed by your diagnostic list
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
                $response = Http::timeout(60)->post("https://generativelanguage.googleapis.com/{$version}/models/{$model}:generateContent?key={$apiKey}", [
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
                $this->error("All attempts failed. Last status: " . ($response ? $response->status() : 'No response'));
                return 1;
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
            
            // Cleanup JSON
            $text = preg_replace('/^```json\s*|\s*```$/i', '', trim($text));
            $afshatData = json_decode($text, true);

            if (!is_array($afshatData)) {
                $this->error('Logic Error: Invalid JSON received.');
                return 1;
            }

            // Get Bot Users
            $botUsers = User::where('is_bot', 1)->orWhere('email', 'like', '%@afchat.fun')->get();
            if ($botUsers->isEmpty()) {
                $botUsers = collect([User::first()]);
            }

            foreach ($afshatData as $item) {
                // Random bot user for setup
                $setupUser = $botUsers->random();
                $setupText = trim($item['setup'] ?? 'موقف بدون نص', " \"'");

                $setup = Setup::create([
                    'user_id' => $setupUser->id,
                    'text' => $setupText,
                    'media_type' => 'text',
                ]);

                $punchline = Punchline::create([
                    'setup_id' => $setup->id,
                    'user_id' => $botUsers->random()->id,
                    'text' => $item['punchline'] ?? '',
                    'media_type' => 'text',
                    'laughs' => rand(5, 50),
                    'views' => rand(100, 500),
                ]);

                // Create AI Comments
                if (isset($item['comments']) && is_array($item['comments'])) {
                    foreach ($item['comments'] as $cBody) {
                        \App\Models\Comment::create([
                            'user_id' => $botUsers->random()->id,
                            'punchline_id' => $punchline->id,
                            'body' => $cBody,
                        ]);
                    }
                }

                // Tags
                if (isset($item['tags']) && is_array($item['tags'])) {
                    $tagIds = [];
                    foreach ($item['tags'] as $tagName) {
                        $tag = Tag::firstOrCreate(['name' => trim($tagName, '# ')]);
                        $tagIds[] = $tag->id;
                    }
                    $setup->tags()->sync($tagIds);
                }

                $this->line("Created: " . Str::limit($setup->text, 40));
            }

            $this->info("Generation successful!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Critical Error: " . $e->getMessage());
            return 1;
        }
    }
}
