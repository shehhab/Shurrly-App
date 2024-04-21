<?php

namespace App\Http\Controllers\Api\Seeker\Explore;

use App\Models\Rate;
use App\Models\Skill;
use App\Models\Advisor;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExploreController extends Controller
{
    public function __invoke()
    {
        $products = Product::with('skills')->get();

        $formattedProducts = $products->map(function ($product) {
                        // Retrieve rates for the current product
            $rates = Rate::where('product_id', $product->id)->pluck('rate');

                        // Calculate average rate
            $averageRate = $rates->isNotEmpty() ? $rates->average() : null;
            $data = [

                'product_id' => $product->id,
                'product_title' => $product->title,
                'product_price' => $product->price,
                'product_cover_photo' => $product->getFirstMediaUrl('cover_product'),
                'skills' => $product->skills->pluck('name')->toArray(), // Access skills for the product
                'categories' => $product->advisor->category->pluck('name')->toArray(),
                'average_rate' => $averageRate,
            ];

            // Check if video_duration is not null and add it to the data
            if ($product->video_duration !== null) {
                $data['video_duration'] = $product->video_duration;
            }

            // Check if pdf_page_count is not null and add it to the data
            if ($product->pdf_page_count !== null) {
                $data['pdf_page_count'] = $product->pdf_page_count;
            }

            return $data;
        });

        $skills = Skill::pluck('name', 'id');

        return $this->handleResponse(data:[
            'Skills' => $skills,
            'products' => $formattedProducts,
        ]);
    }
}
