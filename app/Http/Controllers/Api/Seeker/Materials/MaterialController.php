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

           // Retrieve products with pagination
           $products = $request->has('advisor_id') ?
           Product::whereHas('advisor', function ($query) use ($request) {
               $query->where('id', $request->input('advisor_id'));
           })->paginate($perPage) :
           Product::paginate($perPage);



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
        $paginationData = $this->pagination($products);

        return $this->handleResponse(data:[
            'products' => $formattedProducts,
            'pagination' => $paginationData,

        ]);
    }
}
