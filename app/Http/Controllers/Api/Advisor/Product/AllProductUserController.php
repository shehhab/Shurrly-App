<?php

namespace App\Http\Controllers\Api\Advisor\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Seeker;
use Illuminate\Support\Facades\Auth;

class AllProductUserController extends Controller
{
    public function __invoke()
    {

        $user = Auth::user();

        // Check if the user has an associated advisor
        if (!$user->advisor) {
            return $this->handleResponse(
                status: false,
                code: 404,
                message: 'No advisor associated with this user.',
                data: []
            );
        }

        $perPage = 15;
        $products = $user->advisor->products()->paginate($perPage);

        $responseData = $products->map(function ($product) {
            $data = [
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'advisor_id' => $product->advisor_id,
                'image' => $product->getFirstMediaUrl('cover_product'),
            ];

            if ($videoUrl = $product->getFirstMediaUrl('Product_Video')) {
                $data['Product_Video'] = $videoUrl;
            }

            if ($pdfUrl = $product->getFirstMediaUrl('product_pdf')) {
                $data['product_pdf'] = $pdfUrl;
            }

            if ($product->video_duration !== null) {
                $data['video_duration'] = $product->video_duration;
                $data['type'] = 'video';
            }

            if ($product->pdf_page_count !== null) {
                $data['pdf_page_count'] = $product->pdf_page_count . ' Page';
                $data['type'] = 'PDF';
            }

            return $data;
        });


        return $this->handleResponse(status: true, code: 200, message: 'Products retrieved successfully', data:
        [
            "all_product" => $responseData,
            'pagination' => $this->pagination($products),

        ]
        );
    }
}
