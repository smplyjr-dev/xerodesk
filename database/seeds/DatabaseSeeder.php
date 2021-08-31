<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(SLASeeder::class);

        if (config('app.env') == 'local') {
            // $this->call(ChatbotSeeder::class);
            // $this->call(LocalSeeder::class);
        }
    }
}
