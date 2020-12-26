<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class VoyagerDummyDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedersPath = database_path('seeds').'/';
        $this->seed('Database\Seeders\CategoriesTableSeeder');
        $this->seed('Database\Seeders\UsersTableSeeder');
        $this->seed('Database\Seeders\PostsTableSeeder');
        $this->seed('Database\Seeders\PagesTableSeeder');
        $this->seed('Database\Seeders\TranslationsTableSeeder');
        $this->seed('Database\Seeders\PermissionRoleTableSeeder');
    }
}
