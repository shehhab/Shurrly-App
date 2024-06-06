<?php

namespace App\Console\Commands\Initialization;


use App\Models\Skill;
use Illuminate\Console\Command;


class updateSkills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:skills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'skills Initialize';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    // ---------------skill All ---------------------//

        $skill = Skill::create([
            'name' => 'All',
            'public' => true,
            'categories_id' => 1,
        ]);

    // ---------------skill 1 ---------------------//

        $skill1 = Skill::create([
            'name' => 'Entrepreneurship and Startups',
            'public' => true,
            'categories_id' => 1,
        ]);

        $imagePath1 = asset('Default/Category/Accounting.png');

        $skill1->addMediaFromUrl($imagePath1)->toMediaCollection('image_catogory');

            // ---------------skill 2 ---------------------//


        $skill2 = Skill::create([
                'name' => 'Business Strategy and Management',
                'public' => true,
                'categories_id' => 1,
            ]);

        $imagePath2 = asset('Default/Category/Business.png');

        $skill2->addMediaFromUrl($imagePath2)->toMediaCollection('image_catogory');


            // ---------------skill 3 ---------------------//


        $skill3 = Skill::create([
            'name' => 'Marketing and Sales',
            'public' => true,
            'categories_id' => 1,
        ]);

    $imagePath3 = asset('Default/Category/Economics.png');

    $skill3->addMediaFromUrl($imagePath3)->toMediaCollection('image_catogory');


                // ---------------skill 4 ---------------------//

    $skill4 = Skill::create([
        'name' => 'Branding and Design',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath4 = asset('Default/Category/Finance.png');

    $skill4->addMediaFromUrl($imagePath4)->toMediaCollection('image_catogory');


                // ---------------skill 5 ---------------------//


    $skill5 = Skill::create([
        'name' => 'Finance and Accounting',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath5 = asset('Default/Category/Management.png');

    $skill5->addMediaFromUrl($imagePath5)->toMediaCollection('image_catogory');


                // ---------------skill 6 ---------------------//

    $skill6 = Skill::create([
        'name' => 'Human Resources and Leadership',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath6 = asset('Default/Category/Marketing.png');

    $skill6->addMediaFromUrl($imagePath6)->toMediaCollection('image_catogory');



                // ---------------skill 7 ---------------------//

    $skill7 = Skill::create([
        'name' => 'Technology and Digital Transformation',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath7 = asset('Default/Category/Marketing.png');

    $skill7->addMediaFromUrl($imagePath7)->toMediaCollection('image_catogory');



                // ---------------skill 8 ---------------------//

    $skill8 = Skill::create([
        'name' => 'Operations and Supply Chain Management',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath8 = asset('Default/Category/Marketing.png');

    $skill8->addMediaFromUrl($imagePath8)->toMediaCollection('image_catogory');


                // ---------------skill 9 ---------------------//


    $skill9 = Skill::create([
        'name' => 'Real Estate and Property Management',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath9 = asset('Default/Category/Accounting.png');

    $skill9->addMediaFromUrl($imagePath9)->toMediaCollection('image_catogory');


                // ---------------skill 10 ---------------------//


    $skill10 = Skill::create([
        'name' => 'Career Development and Personal Branding',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath10 = asset('Default/Category/Economics.png');

    $skill10->addMediaFromUrl($imagePath10)->toMediaCollection('image_catogory');


                // ---------------skill 11 ---------------------//


    $skill11 = Skill::create([
        'name' => 'Retail and Hospitality',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath11 = asset('Default/Category/Management.png');

    $skill11->addMediaFromUrl($imagePath11)->toMediaCollection('image_catogory');


                    // ---------------skill 12 ---------------------//

    $skill12 = Skill::create([
        'name' => 'Manufacturing and Engineering',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath12 = asset('Default/Category/Finance.png');

    $skill12->addMediaFromUrl($imagePath12)->toMediaCollection('image_catogory');


                        // ---------------skill 13 ---------------------//

    $skill13 = Skill::create([
        'name' => 'Education and Training',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath13 = asset('Default/Category/Finance.png');

    $skill13->addMediaFromUrl($imagePath13)->toMediaCollection('image_catogory');


                    // ---------------skill 14 ---------------------//

    $skill14 = Skill::create([
        'name' => 'Healthcare Management',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath14 = asset('Default/Category/Finance.png');

    $skill14->addMediaFromUrl($imagePath14)->toMediaCollection('image_catogory');


                    // ---------------skill 15 ---------------------//

    $skill15 = Skill::create([
        'name' => 'Government Relations and Public Policy',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath15 = asset('Default/Category/Finance.png');

    $skill15->addMediaFromUrl($imagePath15)->toMediaCollection('image_catogory');

                    // ---------------skill 16 ---------------------//



    $skill16 = Skill::create([
        'name' => 'Investment Banking and Financial Services',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath16 = asset('Default/Category/Finance.png');

    $skill16->addMediaFromUrl($imagePath16)->toMediaCollection('image_catogory');

                    // ---------------skill 17 ---------------------//



    $skill17 = Skill::create([
        'name' => 'Risk Management and Insurance',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath17 = asset('Default/Category/Finance.png');

    $skill17->addMediaFromUrl($imagePath17)->toMediaCollection('image_catogory');


                    // ---------------skill 18 ---------------------//


    $skill18 = Skill::create([
        'name' => 'Innovation and Creativity',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath18 = asset('Default/Category/Finance.png');

    $skill18->addMediaFromUrl($imagePath18)->toMediaCollection('image_catogory');


                    // ---------------skill 19 ---------------------//


    $skill19 = Skill::create([
        'name' => 'Crisis Management and Communication',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath19 = asset('Default/Category/Finance.png');

    $skill19->addMediaFromUrl($imagePath19)->toMediaCollection('image_catogory');


                    // ---------------skill 20 ---------------------//


    $skill20 = Skill::create([
        'name' => 'Personal Development and Productivity',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath20 = asset('Default/Category/Finance.png');

    $skill20->addMediaFromUrl($imagePath20)->toMediaCollection('image_catogory');


                    // ---------------skill 21 ---------------------//


    $skill21 = Skill::create([
        'name' => 'Franchising',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath21 = asset('Default/Category/Finance.png');

    $skill21->addMediaFromUrl($imagePath21)->toMediaCollection('image_catogory');


                    // ---------------skill 22 ---------------------//


    $skill22 = Skill::create([
        'name' => 'Energy and Sustainability',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath22 = asset('Default/Category/Finance.png');

    $skill22->addMediaFromUrl($imagePath22)->toMediaCollection('image_catogory');


                    // ---------------skill 23 ---------------------//


    $skill23 = Skill::create([
        'name' => 'Investor Relations',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath23 = asset('Default/Category/Finance.png');

    $skill23->addMediaFromUrl($imagePath23)->toMediaCollection('image_catogory');

                    // ---------------skill 24 ---------------------//


    $skill24 = Skill::create([
        'name' => 'Project Management',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath24 = asset('Default/Category/Finance.png');

    $skill24->addMediaFromUrl($imagePath24)->toMediaCollection('image_catogory');


                    // ---------------skill 25 ---------------------//


    $skill25 = Skill::create([
        'name' => 'Project Management',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath25 = asset('Default/Category/Finance.png');

    $skill25->addMediaFromUrl($imagePath25)->toMediaCollection('image_catogory');

                    // ---------------skill 26 ---------------------//



    $skill26 = Skill::create([
        'name' => 'Architecture',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath26 = asset('Default/Category/Finance.png');

    $skill26->addMediaFromUrl($imagePath26)->toMediaCollection('image_catogory');



                    // ---------------skill 27 ---------------------//


    $skill27 = Skill::create([
        'name' => 'Legal and Compliance',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath27 = asset('Default/Category/Finance.png');

    $skill27->addMediaFromUrl($imagePath27)->toMediaCollection('image_catogory');


                    // ---------------skill 28 ---------------------//

    $skill28 = Skill::create([
        'name' => 'Consulting and Coaching',
        'public' => true,
        'categories_id' => 1,
    ]);

    $imagePath28 = asset('Default/Category/Accounting.png');

    $skill28->addMediaFromUrl($imagePath28)->toMediaCollection('image_catogory');


    $this->info('product initialized successfully.');


        return;

    }

}
