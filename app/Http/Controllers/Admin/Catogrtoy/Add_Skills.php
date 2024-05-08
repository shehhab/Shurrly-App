<?php

namespace App\Http\Controllers\Admin\Catogrtoy;

use App\Models\Skill;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Spatie\MediaLibrary\MediaCollections\Exceptions\RequestDoesNotHaveFile;

class Add_Skills extends Controller
{
    public function store()
    {
        $catogroys = DB::table('categories')->select('id', 'name')->get()->toArray();

        return view('admin.categories.add_Skills', compact('catogroys'));


    }

public function index(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string',
        'categories_id' => 'required|exists:categories,id', // Assuming 'categories' is the correct table name
        'image_catogory'=>['required','image','mimes:jpg,jpeg,png'],

    ]);

    $existingProduct = Skill::where('name', $request->name)->first();
    if ($existingProduct) {
        return redirect()->back()->with('existing_product', 'This Skill already exists!');
    }

    try {
        // To add a new skill in the skill table and update advisor skills
        $skill = Skill::create([
            'name' => $data['name'],
            'categories_id' => $data['categories_id'],
        ]);

        // Upload image if present
        if ($request->hasFile('image_catogory')) {
            $skill->addMediaFromRequest('image_catogory')->toMediaCollection('image_catogory');
        }

        Session::flash('success', 'Successfully Add Skill');
        // Redirect to the desired page
        return redirect('/dashboard/all_Skills');
    } catch (RequestDoesNotHaveFile $e) {
        // Handle case when no image is provided
        return redirect()->back()->with('error', 'Please provide an image.');
    }
}
}
