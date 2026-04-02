<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class GeminiController extends Controller
{
    public function generate(Request $request)
    {
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
}
