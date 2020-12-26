<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class VoyagerDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed('Database\Seeders\DataTypesTableSeeder');
        $this->seed('Database\Seeders\DataRowsTableSeeder');
        $this->seed('Database\Seeders\MenusTableSeeder');
        $this->seed('Database\Seeders\MenuItemsTableSeeder');
        $this->seed('Database\Seeders\RolesTableSeeder');
        $this->seed('Database\Seeders\PermissionsTableSeeder');
        $this->seed('Database\Seeders\PermissionRoleTableSeeder');
        $this->seed('Database\Seeders\SettingsTableSeeder');
    }
}
