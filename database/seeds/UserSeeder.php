<?php

use App\Models\Role\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now   = now();
        $pword = bcrypt('V67rNEXY');
        $super = Role::findOrFail(1);
        $admin = Role::findOrFail(2);
        $agent = Role::findOrFail(3);

        $dani = User::create(['company_id' => 1, 'username' => 'dani.xerosoft', 'email' => 'dani@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword, 'profile_picture' => 'Dani-5fa52fc9a848b.png']);
        $dani->assignRole($super);
        $dani->bio()->create(['first_name' => 'Dan', 'last_name' => 'Chris']);

        $jerome = User::create(['company_id' => 1, 'username' => 'jerome.xerosoft', 'email' => 'jerome@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword, 'profile_picture' => 'Jerome-5fa52fff8e24d.png']);
        $jerome->assignRole($admin);
        $jerome->bio()->create(['first_name' => 'Jerome', 'last_name' => 'Dymosco']);

        $alfredo = User::create(['company_id' => 1, 'username' => 'alfredo.xerosoft', 'email' => 'alfredo@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword, 'profile_picture' => 'Alfredo-5fa53018f0d38.jpeg']);
        $alfredo->assignRole($agent);
        $alfredo->bio()->create(['first_name' => 'Alfredo', 'last_name' => 'Flores']);
    }
}
