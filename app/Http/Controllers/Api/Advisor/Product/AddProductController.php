<?php

namespace App\Http\Controllers\Api\Advisor\Product;

use App\Models\Skill;
use App\Models\Product;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advisor\Product\AddProductRequest;
use App\Http\Resources\Advisor\Product\ProductResources;



class AddProductController extends Controller
{

    public function __invoke(AddProductRequest $request)
    {
        $validatedData = $request->validated();
        $advisor = request()->user();

        // ربط المهارات بالمنتج
        foreach ($request->skills as $skillName) {
            $skill = Skill::where('name', $skillName)->first();
            if (!$skill) {
                // إذا لم يتم العثور على المهارة، قم بإرجاع رسالة خطأ وانهي التنفيذ
                return $this->handleResponse(message: 'Skill not found: ' . '('.$skillName.')', status: false, code: 422);
            }
        }

        // بعد التحقق من وجود جميع المهارات بنجاح، يمكنك تنفيذ بقية الخطوات هنا

        // إنشاء المنتج
        $product = Product::create([
           // 'name' => $validatedData['name'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'advisor_id' => $validatedData['advisor_id'],
        ]);

        // ربط المهارات بالمنتج
        foreach ($request->skills as $skillName) {
            $skill = Skill::where('name', $skillName)->first();
            $product->skills()->attach($skill->id);
        }


        // تحميل الصورة المصغرة
        if ($request->hasFile('image')) {
            $product->addMediaFromRequest('image')->toMediaCollection('cover_product');
        }

        // تحميل ملف PDF
        if ($request->hasFile('product_pdf')) {
            $product->addMediaFromRequest('product_pdf')->toMediaCollection('product_pdf');
            // حساب عدد الصفحات في ملف PDF وحفظها في قاعدة البيانات
            $pageCount = $this->getPdfPageCount($product->getFirstMedia('product_pdf')->getPath());
            $product->update([
                'pdf_page_count' => $pageCount,
            ]);
        }

        // تحميل فيديو المنتج
        if ($request->hasFile('Product_Video')) {
            $mediaItem = $product->addMediaFromRequest('Product_Video')->toMediaCollection('Product_Video');
            // حساب مدة الفيديو وحفظها في قاعدة البيانات
            $duration = $this->getVideoDuration($mediaItem->getPath());
            $product->update([
                'video_duration' => $duration,
            ]);
        }

        return $this->handleResponse(status:true, code:200 ,message:'Upload Product Successfully', data: new ProductResources($product));
    }

    private function getVideoDuration($filePath)
    {
        $getID3 = new \getID3;
        // Analyze the video file
        $fileInfo = $getID3->analyze($filePath);
        // Get the duration of the video in seconds
        $durationInSeconds = $fileInfo['playtime_seconds'];
        // Calculate hours
        $hours = floor($durationInSeconds / 3600);
        // Calculate remaining minutes
        $minutes = floor(($durationInSeconds / 60) % 60);
        // Calculate remaining seconds
        $seconds = $durationInSeconds % 60;

        // Format duration as "hours:minutes:seconds"
        $durationFormatted = sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);

        return $durationFormatted ;
    }

    private function getPdfPageCount($filePath)
    {
        // Initialize the PDF parser
        $parser = new Parser();
        // Parse the PDF file
        $pdf = $parser->parseFile($filePath);
        // Get the number of pages
        return count($pdf->getPages());
    }
}


