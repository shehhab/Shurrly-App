<?php

namespace App\Http\Controllers\Api\Seeker\Materials;

use App\Models\Rate;
use App\Models\Skill;
use App\Models\Advisor;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    public function __invoke(Request $request)
    {
        // Set the number of items per page
        $perPage = 6;

        // Retrieve the skill from the request
        $skill = $request->input('skill');

        // Start building the query to retrieve products
        $query = Product::query();

        if ($skill && $skill !== 'All') {
            $query->whereHas('skills', function ($query) use ($skill) {
                $query->where('name', $skill);
            });
        }

        // Paginate the results
        $products = $query->paginate($perPage);

        // Format the products as before
        $formattedProducts = $products->map(function ($product) {
            // Retrieve rates for the current product
            $rates = Rate::where('product_id', $product->id)->pluck('rate');


            // Calculate average rate
            $averageRate = $rates->isNotEmpty() ? $rates->average() : 0;
            $data = [
                'product_id' => $product->id,
                'product_title' => $product->title,
                'product_price' => $product->price,
                'skills' => $product->skills->pluck('name')->toArray(),
                'product_cover_photo' => $product->getFirstMediaUrl('cover_product'),
                'categories' => $product->advisor->category->pluck('name')->toArray(),
                'average_rate' => number_format($averageRate, 1) ?? 0,

            ];

            // Check if video_duration is not null and add it to the data
            if ($product->video_duration !== null) {
                $data['video_duration'] = $product->video_duration;

                $durationInSeconds = strtotime("1970-01-01 " . $data['video_duration'] . " UTC");

                // تحويل المدة إلى تنسيق مختصر
                if ($durationInSeconds < 60) {
                    $formattedDuration = $durationInSeconds . " ".'Sec'; // ثواني
                }
                elseif ($durationInSeconds < 3600) {
                    $minutes = gmdate("i", $durationInSeconds);
                    $seconds = gmdate("s", $durationInSeconds);
                    if ($seconds >= 30) {
                        $minutes++; // زيادة الدقائق
                        $seconds = 0; // إعادة الثواني إلى صفر
                    }
                    $formattedDuration = $minutes ." ". 'Min' ; // دقائق وثواني
                }




                else {
          // إذا كانت المدة تزيد عن ساعة، يتم عرض الساعات والدقائق
          $hours = gmdate("H", $durationInSeconds);
          $minutes = gmdate("i", $durationInSeconds);
          $formattedDuration = $hours . ':' . $minutes; // ساعات ودقائق
                }

                // تعيين المدة المحولة إلى البيانات
                $data['video_duration'] = $formattedDuration;

                // إضافة القيم الإضافية
                $data['type'] = "video";
                $data['video'] = $product->getFirstMediaUrl('Product_Video');
            }

            // Check if pdf_page_count is not null and add it to the data
            if ($product->pdf_page_count !== null) {
                $data['pdf_page_count'] = $product->pdf_page_count;
                $data['type'] = "pdf";

                $data['product_pdf'] =$product->getFirstMediaUrl('product_pdf');
            }

            return $data;
        });

        $paginationData = $this->pagination($products);

        return $this->handleResponse(data:[
            'products' => $formattedProducts,
            'pagination' => $paginationData,

        ]);
    }
}
