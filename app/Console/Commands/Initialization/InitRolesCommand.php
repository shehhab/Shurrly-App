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


        $skill = Skill::create([
            'name' => 'All',
            'public' => true,
            'categories_id' => 1,
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
        $userAdvisor->addMediaFromUrl($imagePathAdvisor1)->toMediaCollection('advisor_profile_image');



        $CertificatesPathAdvisor1 = asset('Default/Category/cv.pdf');

        $userAdvisor->addMediaFromUrl($CertificatesPathAdvisor1)->toMediaCollection('advisor_Certificates_PDF');

        $videoPathAdvisor1 = asset('Default/Category/vi.mp4');

        $userAdvisor->addMediaFromUrl($videoPathAdvisor1)->toMediaCollection('advisor_Intro_video');




        $userAdvisor1 = Advisor::create([
            'seeker_id' => 3 ,
            'bio' => 'This might refer to an Englis biologsuch as sciological concepts in English',
            'language' => 'English',
            'offere' => 100,
            'approved'=> 1 ,
            'days[0][day]' =>'Monday',
            'days[0][from]' => '05:00',
            'days[0][to]' => '10:00',
            'skills[]' =>'Accounting',
            'country'=>'egypt',
            'category_id'=>1 ,
            'session_duration'=>'00:30:00',

        ]);

        $user2->assignRole('advisor');

        $skill = Skill::where('name', 'Accounting')->first();
        $userAdvisor1->skills()->attach($skill->id);
        $userAdvisor1->save();

        $imagePathAdvisor2 = asset('Default/Category/4.jpg');
        $userAdvisor1->addMediaFromUrl($imagePathAdvisor2)->toMediaCollection('advisor_profile_image');



        $CertificatesPathAdvisor2 = asset('Default/Category/cv.pdf');

        $userAdvisor1->addMediaFromUrl($CertificatesPathAdvisor2)->toMediaCollection('advisor_Certificates_PDF');

        $videoPathAdvisor2 = asset('Default/Category/vi.mp4');

        $userAdvisor1->addMediaFromUrl($videoPathAdvisor2)->toMediaCollection('advisor_Intro_video');



        //user seeker and advisor 3
        $user3 = Seeker::create([
            'name' => 'user 3',
            'email' => 'shehab3@gmail.com',
            'password' => Hash::make('She0011998877@'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user3->assignRole('seeker');

        $userAdvisor3 = Advisor::create([
            'seeker_id' => 4 ,
            'bio' => '
            Laravel is a free and open-source PHP web framework created by Taylor Otwell. Laravel features expressive, elegant syntax - freeing
            ',
            'language' => 'French',
            'offere' => 500,
            'approved'=> 1 ,
            'days[0][day]' =>'Tuesday',
            'days[0][from]' => '01:00',
            'days[0][to]' => '05:00',
            'skills[]' =>'Management',
            'country'=>'egypt',
            'category_id'=>1 ,
            'session_duration'=>'00:45:00',

        ]);

        $user3->assignRole('advisor');

        $skill = Skill::where('name', 'Management')->first();
        $userAdvisor3->skills()->attach($skill->id);
        $userAdvisor3->save();

        $imagePathAdvisor3 = asset('Default/Category/4.jpg');
        $userAdvisor3->addMediaFromUrl($imagePathAdvisor3)->toMediaCollection('advisor_profile_image');



        $CertificatesPathAdvisor3 = asset('Default/Category/cv.pdf');

        $userAdvisor3->addMediaFromUrl($CertificatesPathAdvisor3)->toMediaCollection('advisor_Certificates_PDF');

        $videoPathAdvisor3 = asset('Default/Category/vi.mp4');

        $userAdvisor3->addMediaFromUrl($videoPathAdvisor3)->toMediaCollection('advisor_Intro_video');









        //user seeker and advisor 4

        $user4 = Seeker::create([
            'name' => 'user 4',
            'email' => 'shehab4@gmail.com',
            'password' => Hash::make('She0011998877@'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user4->assignRole('seeker');

        $userAdvisor4 = Advisor::create([
            'seeker_id' => 5 ,
            'bio' => '
            Filament is the Swiss Army Knife dashboard for TALL stack apps . Just sit down, install and you',
            'language' => 'Ital',
            'offere' => 600,
            'approved'=> 1 ,
            'days[0][day]' =>'Sunday',
            'days[0][from]' => '13:00',
            'days[0][to]' => '18:00',
            'skills[]' =>'Marketing',
            'country'=>'egypt',
            'category_id'=>1 ,
            'session_duration'=>'00:45:00',

        ]);

        $user4->assignRole('advisor');

        $skill = Skill::where('name', 'Marketing')->first();
        $userAdvisor4->skills()->attach($skill->id);
        $userAdvisor4->save();

        $imagePathAdvisor4 = asset('Default/Category/4.jpg');
        $userAdvisor4->addMediaFromUrl($imagePathAdvisor4)->toMediaCollection('advisor_profile_image');



        $CertificatesPathAdvisor4 = asset('Default/Category/cv.pdf');

        $userAdvisor4->addMediaFromUrl($CertificatesPathAdvisor4)->toMediaCollection('advisor_Certificates_PDF');

        $videoPathAdvisor4 = asset('Default/Category/vi.mp4');

        $userAdvisor4->addMediaFromUrl($videoPathAdvisor4)->toMediaCollection('advisor_Intro_video');






        $user2 = RateAdvisor::create([
            'advisor_id' => 1,
            'seeker_id' => 1,
            'rate' =>5,
        ]);


        $this->info('Roles and skills initialized successfully.');


        return;

    }

}
