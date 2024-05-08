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
        $roles = ['admin', 'advisor', 'seeker'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard' => 'web']);
        }


        // Skill data initialization
        $skillData = [
            ['name' => 'Business', 'public' => true, 'category_id' => 1, 'image_path' => 'Default/Category/Business.PNG'],
            ['name' => 'Economics', 'public' => true, 'category_id' => 1, 'image_path' => 'Default/Category/Economics.PNG'],
            ['name' => 'Finance', 'public' => true, 'category_id' => 1, 'image_path' => 'Default/Category/Finance.PNG'],
            ['name' => 'Management', 'public' => true, 'category_id' => 1, 'image_path' => 'Default/Category/Management.PNG'],
            ['name' => 'Marketing', 'public' => true, 'category_id' => 1, 'image_path' => 'Default/Category/Marketing.PNG'],
        ];

        foreach ($skillData as $data) {
            $skill = Skill::firstOrCreate([
                'name' => $data['name'],
                'public' => $data['public'],
                'category_id' => $data['category_id'],
            ]);

            $imagePath = asset($data['image_path']);
            $skill->addMediaFromUrl($imagePath)->toMediaCollection('image_category');
        }

        $this->info('Roles and skills initialized successfully.');



        return;

    }

}
