<?php

namespace App\Http\Controllers\Api\core\GetData;

use App\Models\Skill;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetDataSkillController extends Controller
{
    public function __invoke(){
    $perPage = 6;
    $skills = Skill::select('id', 'name')->paginate($perPage);
    $categories = Category::select('id', 'name')->paginate($perPage);

    $data = [
        'categories' => [
            'data' => $categories->items(),
            'pagination' => $this->pagination($categories)
        ],
        'skills' => [
            'data' => $skills->items(),
            'pagination' => $this->pagination($skills)
        ],
    ];

        return $this->handleResponse(data: $data, status: true, code: 200);

    }
}
