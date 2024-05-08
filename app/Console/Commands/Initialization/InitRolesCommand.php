<?php

namespace App\Console\Commands\Initialization;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class InitRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-roles-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Roles Initialize';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Role::create([
            'name' => 'admin',
            'guard'=>'web'
        ]);
        Role::create([
            'name' => 'advisor',
            'guard'=>'web'
        ]);

        Role::create([
            'name' => 'seeker',
            'guard'=>'web'
        ]);

        $skill1 = Skill::create([
            'name' => 'Accounting',
            'public' => true,
            'categories_id' => 1,
        ]);

        $imagePath1 = asset('Default/Category/Accounting.png');

        $skill1->addMediaFromUrl($imagePath1)->toMediaCollection('image_catogory');


        $skill2 = Skill::create([
                'name' => 'Business',
                'public' => true,
                'categories_id' => 1,
            ]);

        $imagePath2 = asset('Default/Category/Business.png');

        $skill2->addMediaFromUrl($imagePath2)->toMediaCollection('image_catogory');




        $skill3 = Skill::create([
            'name' => 'Economics',
            'public' => true,
            'categories_id' => 1,
        ]);

    $imagePath3 = asset('Default/Category/Economics.png');

    $skill3->addMediaFromUrl($imagePath3)->toMediaCollection('image_catogory');

    $skill4 = Skill::create([
        'name' => 'Finance',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath4 = asset('Default/Category/Finance.png');

    $skill4->addMediaFromUrl($imagePath4)->toMediaCollection('image_catogory');



    $skill5 = Skill::create([
        'name' => 'Management',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath5 = asset('Default/Category/Management.png');

    $skill5->addMediaFromUrl($imagePath5)->toMediaCollection('image_catogory');



    $skill6 = Skill::create([
        'name' => 'Marketing',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath6 = asset('Default/Category/Marketing.png');

    $skill6->addMediaFromUrl($imagePath6)->toMediaCollection('image_catogory');

        $this->info('Roles and skills initialized successfully.');



        return;

    }

}
