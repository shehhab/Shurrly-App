<?php

namespace App\Http\Controllers\Api\Seeker\Materials;

use App\Models\SavedProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Auth\GetProfileResource;
use App\Http\Resources\Auth\SavedProductResource;

class ViewProductSavedController extends Controller
{
    public function __invoke(Request $request)
    {
        $seeker_id = $request->user()->id;

        $savedProducts = SavedProduct::with('product')->where('seeker_id', $seeker_id)->get();

        $savedProductsResource = $savedProducts->map(function($savedProduct) {
            $product = $savedProduct->product;
            $formattedProduct = [
                'product_id' => $product->id,
                'title' => $product->title,
                'cover_product' => $product->getFirstMediaUrl('cover_product'), // assuming it's directly available
                'skills' =>  $product->skills->pluck('name')->toArray(), // assuming it's directly available
            ];

            if ($product->pdf_page_count !== null) {
                $formattedProduct['pdf_page_count'] = $product->pdf_page_count;
                $formattedProduct['type'] = 'pdf';
                $formattedProduct['pdf'] = $product->getFirstMediaUrl('product_pdf');
            } elseif ($product->video_duration !== null) {
                $formattedProduct['video_duration'] = $product->video_duration;
                $formattedProduct['type'] = 'video';
                $formattedProduct['video'] = $product->getFirstMediaUrl('Product_Video');
            }

            return $formattedProduct;
        });

        return $this->handleResponse(data:[
            'saved_products' => $savedProductsResource,

        ],

        code : 200);
    }
}
