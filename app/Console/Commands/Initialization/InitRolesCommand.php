<?php

namespace App\Console\Commands\Initialization;

use App\Models\Advisor;
use App\Models\Skill;
use App\Models\Seeker;
use App\Models\Category;
use App\Models\RateAdvisor;
use App\Models\SessionSchedule;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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



           $user = Seeker::create([
            'name' => 'hussein',
            'email' => 'husseinhtm99@gmail.com',
            'password' => Hash::make('Hussein123@'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            $user->assignRole('seeker');


        $user1 = Seeker::create([
            'name' => 'user 1',
            'email' => 'shehab1@gmail.com',
            'password' => Hash::make('She0011998877@'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user1->assignRole('seeker');

        $user2 = Seeker::create([
            'name' => 'user 2',
            'email' => 'shehab2@gmail.com',
            'password' => Hash::make('She0011998877@'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user2->assignRole('seeker');


        $userAdvisor = Advisor::create([
            'seeker_id' => 2 ,
            'bio' => 'This might refer to an Englis biologsuch as sciological concepts in English',
            'language' => 'Arabic',
            'offere' => 50,
            'approved'=> 1 ,
            'days[0][day]' =>'Tuesday',
            'days[0][from]' => '01:00',
            'days[0][to]' => '05:00',
            'skills[]' =>'Economics',
            'country'=>'egypt',
            'category_id'=>1 ,
            'session_duration'=>'00:30:00',

        ]);

        $user1->assignRole('advisor');

        $skill = Skill::where('name', 'Economics')->first();
        $userAdvisor->skills()->attach($skill->id);
        $userAdvisor->save();

        $imagePathAdvisor1 = asset('Default/Category/3.jpg');
        $user1->addMediaFromUrl($imagePathAdvisor1)->toMediaCollection('advisor_profile_image');




        $this->info('Roles and skills initialized successfully.');


        return;

    }

}
