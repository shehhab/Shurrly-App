<?php

namespace App\Http\Controllers\Api\core\GetData;

use App\Models\Skill;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetDataSkillController extends Controller
{
    public function __invoke()
    {
        $perPage = 6;
        $categories = Category::select('id', 'name')->paginate($perPage);

        $data = [
            'category' => [
                'data' => $categories->map(function ($category) {
                    // Get skills for each category
                    $skills = Skill::where('categories_id', $category->id)->get();

                    // Get the URL of the worker's profile image from the 'image_category' collection for each skill
                    $profileImages = $skills->map(function ($skill) {
                        $profileImage = $skill->getFirstMedia('image_catogory');
                        $profileImageUrl = $profileImage ? $profileImage->getUrl() : asset('Default/profile.jpeg');
                            return [
                            'id' => $skill->id,
                            'name' => $skill->name,
                            'profile_image_url' => $profileImageUrl,
                        ];
                    });

                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'skills' => $profileImages,
                    ];
                }),
            ],
        ];

        return $this->handleResponse(data: $data, status: true, code: 200);
    }
}
