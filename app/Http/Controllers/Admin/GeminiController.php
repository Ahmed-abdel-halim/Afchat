<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class GeminiController extends Controller
{
    public function generate(Request $request)
    {
        set_time_limit(0); 
        $count = $request->input('count', 5);

        try {
            $exitCode = Artisan::call('app:generate-afshat', ['count' => $count]);
            $output = Artisan::output();

            if ($exitCode === 0) {
                return back()->with('success', "تم توليد {$count} مواقف جديدة بنجاح بواسطة جيمناي! وتم توزيعها على حسابات عشوائية.");
            } else {
                // Detecting quota error to give a friendly message if it exists in output
                if (str_contains(strtolower($output), 'quota')) {
                    $errorMessage = "انتهت الحصة المجانية لمفتاح Gemini (Quota Exceeded). يرجى تغيير المفتاح في ملف .env أو المحاولة لاحقاً.";
                } else {
                    $errorMessage = !empty($output) ? "حدث خطأ أثناء التوليد:\n" . $output : 'فشل التوليد. تأكد من إعداد GEMINI_API_KEY في ملف .env وتوفر حسابات بوت.';
                }
                return back()->with('error', nl2br($errorMessage));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ تقني: ' . $e->getMessage());
        }
    }

    public function importFromJson(Request $request)
    {
        set_time_limit(0); // Ensure long imports don't timeout
        
        // Relaxed validation to avoid MIME issues with .txt files
        $request->validate([
            'json_file' => 'required|file',
        ]);

        try {
            $fileContent = file_get_contents($request->file('json_file')->path());
            
            // Try to convert to UTF-8 if it's not already
            if (!mb_check_encoding($fileContent, 'UTF-8')) {
                $fileContent = mb_convert_encoding($fileContent, 'UTF-8', 'ISO-8859-1, Windows-1256, ASCII');
            }
            
            // CLEANUP: Remove Markdown wrappers (```json ... ```) if they exist
            $fileContent = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $fileContent);
            $fileContent = trim($fileContent);

            // FIX: Replace Arabic commas with standard English commas
            $fileContent = str_replace('،', ',', $fileContent);
            
            $afshatData = json_decode($fileContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'خطأ في قراءة JSON: ' . json_last_error_msg() . '. تأكد أن الملف نصي (UTF-8) ولا يحتوي على أخطاء برمجية.');
            }

            if (!is_array($afshatData)) {
                return back()->with('error', 'الملف لا يحتوي على مصفوفة بيانات صحيحة.');
            }

            // Get Bot Users
            $botUsers = \App\Models\User::where('is_bot', 1)->orWhere('email', 'like', '%@afchat.fun')->get();
            if ($botUsers->isEmpty()) {
                $botUsers = collect([\App\Models\User::first()]);
            }

            $createdCount = 0;
            foreach ($afshatData as $item) {
                // Random bot user for setup
                $setupUser = $botUsers->random();
                $setupText = trim($item['setup'] ?? 'موقف بدون نص', " \"'");

                $setup = \App\Models\Setup::create([
                    'user_id' => $setupUser->id,
                    'text' => $setupText,
                    'media_type' => 'text',
                ]);

                $punchlinesData = (array) ($item['punchline'] ?? []);
                // Support both single string and array
                if (empty($punchlinesData) && !empty($item['punchline'])) {
                    $punchlinesData = [$item['punchline']];
                }

                foreach ($punchlinesData as $pText) {
                    $punchline = \App\Models\Punchline::create([
                        'setup_id' => $setup->id,
                        'user_id' => $botUsers->random()->id,
                        'text' => $pText,
                        'media_type' => 'text',
                        'laughs' => rand(5, 50),
                        'views' => rand(100, 500),
                    ]);

                    // Create AI Comments for EACH punchline if provided
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

                // Tags for the original setup
                if (isset($item['tags']) && is_array($item['tags'])) {
                    $tagIds = [];
                    foreach ($item['tags'] as $tagName) {
                        $tag = \App\Models\Tag::firstOrCreate(['name' => trim($tagName, '# ')]);
                        $tagIds[] = $tag->id;
                    }
                    $setup->tags()->sync($tagIds);
                }
                $createdCount++;
            }

            return back()->with('success', "تم استيراد {$createdCount} قفشات بنجاح من الملف!");

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء معالجة الملف: ' . $e->getMessage());
        }
    }
}
