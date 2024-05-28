<?php

namespace App\Http\Controllers\Api\Seeker\Materials;

use App\Models\Rate;
use App\Models\Seeker;
use App\Models\Product;
use App\Models\SavedProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PageProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $productId = $request->input('product_id');
        $user = Auth::user();

        $product = Product::find($productId);

        if (!$product) {
            return $this->handleResponse(status:false, message: 'Product not found', code: 404);
        }

        // Get default image URL
        $defaultImage = asset('Default/profile.jpeg');
        $rates = Rate::where('product_id', $product->id)->pluck('rate');

        $averageRate = $rates->isNotEmpty() ? $rates->average() : 0;

        $isSaved = $user->savedProducts()->where('product_id', $product->id)->exists();


        $formattedProduct = [
            'product_id' => $product->id,
            'product_title' => $product->title,
            'created_by' => $product->advisor->seeker->name,
            'product_price' => $product->price,
            'created_at' => $product->created_at->format('j,Y'),
            'product_description' => $product->description,
            'product_cover_photo' => $product->getFirstMediaUrl('cover_product'),
            'average_rate' => number_format($averageRate, 1) ?? 0,
            'skills' => $product->skills->pluck('name')->toArray(),
            // ! is for test edite when payment complete
            'ispaid'=> false ,
            'is_saved' => $isSaved,
        ];

        if ($product->video_duration !== null) {
            $formattedProduct['video_duration'] = $product->video_duration;

            $durationInSeconds = strtotime("1970-01-01 " . $product->video_duration . " UTC");

            if ($durationInSeconds < 60) {
                $formattedDuration = $durationInSeconds . " ".'Sec';
            } elseif ($durationInSeconds < 3600) {
                $minutes = gmdate("i", $durationInSeconds);
                $seconds = gmdate("s", $durationInSeconds);
                if ($seconds >= 30) {
                    $minutes++;
                    $seconds = 0;
                }
                $formattedDuration = $minutes ." ". 'Min' ;
            } else {
                $hours = gmdate("H", $durationInSeconds);
                $minutes = gmdate("i", $durationInSeconds);
                $formattedDuration = $hours . ':' . $minutes;
            }

            $formattedProduct['video_duration'] = $formattedDuration;
            $formattedProduct['type'] = 'video';
            $formattedProduct['video'] = $product->getFirstMediaUrl('Product_Video');
        }


        if ($product->pdf_page_count !== null) {
            $formattedProduct['pdf_page_count'] = $product->pdf_page_count;
            $formattedProduct['type'] =  'pdf' ;

            // ! this is remove when complete payment

            $formattedProduct['pdf'] =$product->getFirstMediaUrl('product_pdf');

        }

        return $this->handleResponse(status:true , data: $formattedProduct);
    }
}
