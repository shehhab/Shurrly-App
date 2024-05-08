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
        $skills = Skill::select('id', 'name')->paginate($perPage);

        $data = [
            'skills' => [
                'data' => $skills->map(function ($skill) {
                    // Get the URL of the worker's profile image from the 'image_catogory' collection for each skill
                    $profileImage = $skill->getFirstMedia('image_catogory');
                    $profileImageUrl = $profileImage ? $profileImage->getUrl() : asset('Default/profile.jpeg');

                    return [
                        'id' => $skill->id,
                        'name' => $skill->name,
                        'profile_image_url' => $profileImageUrl,
                    ];
                }),
                'pagination' => $this->pagination($skills),
            ],
        ];

        return $this->handleResponse(data: $data, status: true, code: 200);
    }
}
