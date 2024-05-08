<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skill1 = Skill::create([
            'name' => 'Accounting',
            'public' => true,
            'categories_id' => 1,
        ]);
        $imagePath = asset('Default\Category\Accounting.PNG');
        $skill1->addMediaFromUrl($imagePath)->toMediaCollection('image_catogory');



        $skill2 = Skill::create([
            'name' => 'Business',
            'public' => true,
            'categories_id' => 1,
        ]);
        $imagePath = asset('Default\Category\Business.PNG');
        $skill2->addMediaFromUrl($imagePath)->toMediaCollection('image_catogory');


        $skill3 = Skill::create([
            'name' => 'Economics',
            'public' => true,
            'categories_id' => 1,
        ]);
        $imagePath = asset('Default\Category\Economics.PNG');
        $skill3->addMediaFromUrl($imagePath)->toMediaCollection('image_catogory');

        $skill4 = Skill::create([
            'name' => 'Finance',
            'public' => true,
            'categories_id' => 1,
        ]);
        $imagePath = asset('Default\Category\Finance.PNG');
        $skill4->addMediaFromUrl($imagePath)->toMediaCollection('image_catogory');

        $skill5 = Skill::create([
            'name' => 'Management',
            'public' => true,
            'categories_id' => 1,
        ]);
        $imagePath = asset('Default\Category\Management.PNG');
        $skill5->addMediaFromUrl($imagePath)->toMediaCollection('image_catogory');



        $skill6 = Skill::create([
            'name' => 'Marketing',
            'public' => true,
            'categories_id' => 1,
        ]);
        $imagePath = asset('Default\Category\Marketing.PNG');
        $skill6->addMediaFromUrl($imagePath)->toMediaCollection('image_catogory');




    }


}
