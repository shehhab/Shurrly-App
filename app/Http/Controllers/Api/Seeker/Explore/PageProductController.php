<?php

namespace App\Http\Controllers\Api\Seeker\Explore;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Seeker;

class PageProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $productId = $request->input('product_id');

        $product = Product::with('ratings')->find($productId);

        if (!$product) {
            return $this->handleResponse(status:false, message: 'Product not found', code: 404);
        }

        // Get default image URL
        $defaultImage = asset('Default/profile.jpeg');

        $formattedProduct = [
            'product_id' => $product->id,
            'product_title' => $product->title,
            'product_price' => $product->price,
            'product_description' => $product->description,
            'product_cover_photo' => $product->getFirstMediaUrl('cover_product'),
            'skills' => $product->skills->pluck('name')->toArray(),
            'ratings' => $product->ratings->map(function($rating) use ($defaultImage) {
                // Check if seeker_profile_image exists, otherwise use default image
                $seekerProfileImage = $rating->seeker->getFirstMediaUrl('seeker_profile_image');
                $seekerProfileImageUrl = $seekerProfileImage ? $seekerProfileImage : $defaultImage;

                return [
                    'rate' => $rating->rate,
                    'message' => $rating->message,
                    'create_message' => $rating->created_at,
                    'seeker_name' => $rating->seeker->name,
                    'seeker_profile_image' => $seekerProfileImageUrl,
                ];
            })->toArray(),
        ];

        if ($product->video_duration !== null) {
            $formattedProduct['video_duration'] = $product->video_duration;
        }

        if ($product->pdf_page_count !== null) {
            $formattedProduct['pdf_page_count'] = $product->pdf_page_count;
        }

        return $this->handleResponse(status:true , data: $formattedProduct);
    }
}
