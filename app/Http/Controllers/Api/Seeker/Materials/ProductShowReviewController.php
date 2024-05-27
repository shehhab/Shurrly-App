<?php

namespace App\Http\Controllers\Api\Seeker\Materials;

use App\Models\Rate;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Support\Carbon;
class ProductShowReviewController extends Controller
{
    public function __invoke(Request $request)
    {
        $productId = $request->input('product_id');

        $product = Product::find($productId);

        if (!$request->has('product_id')) {
            return $this->handleResponse(message: 'Product ID is required', code: 400, status: false);
        }

        if (!$product) {
            return $this->handleResponse(status: false, message: 'Product not found', code: 404);
        }

        $perPage= 15 ;
        $ratings = $product->ratings()->paginate($perPage);
        $defaultImage = asset('Default/profile.jpeg');

        // حساب متوسط ​​التقييمات
        $averageRating = $ratings->avg('rate');
        $formattedAverageRating = number_format($averageRating, 1);

        $ratingsData = [];
        foreach ($ratings as $rating) {
            $createdAt = Carbon::parse($rating->created_at);
            $createdAtFormatted = $this->daysBetween($createdAt);
            $seekerProfileImage = $rating->seeker->getFirstMediaUrl('seeker_profile_image');
            $seekerProfileImageUrl = $seekerProfileImage ? $seekerProfileImage : $defaultImage;

            $ratingsData[] = [
                'id' => $rating->id,
                'name' => $rating->seeker->name,
                'rating' => $rating->rate,
                'message' => $rating->message,
                'seeker_profile_image' => $seekerProfileImageUrl,
                'created_at' =>$createdAtFormatted,

            ];
        }
       // $paginationData = $this->pagination($ratingsData);


        return $this->handleResponse(
            message: 'Ratings retrieved successfully',
            data: [
                'total_rate' => $formattedAverageRating,
                'review' => $ratingsData,
                'pagination' => $this->pagination($ratings),

                ]
        );
    }

    private function daysBetween($postDate)
    {
        $now = new DateTime();
        $difference = $now->diff($postDate);

        $days = $difference->days;
        $hours = $difference->h;
        $minutes = $difference->i;

        if ($days == 0) {
            if ($hours == 0) {
                if ($minutes == 0) {
                    return 'now';
                } else {
                    return $minutes . ' minutes';
                }
            } else {
                return $hours . ' hours ' . $minutes . ' minutes';
            }
        } else {
            return $postDate->format('l, d/m/Y');
        }
    }
    }
