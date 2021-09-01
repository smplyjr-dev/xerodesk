<?php

use App\Models\Role\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        $pword = bcrypt('V67rNEXY');

        $ad = Role::findOrFail(2);
        $ag = Role::findOrFail(3);

        $admin = User::create(['company_id' => 1, 'username' => 'admin.xerosoft', 'email' => 'admin@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword]);
        $admin->assignRole($ad);
        $admin->bio()->create(['first_name' => 'Admin', 'last_name' => 'Xerosoft']);

        $agent = User::create(['company_id' => 1, 'username' => 'agent.xerosoft', 'email' => 'agent@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword]);
        $agent->assignRole($ag);
        $agent->bio()->create(['first_name' => 'Agent', 'last_name' => 'Xerosoft']);
    }
}
