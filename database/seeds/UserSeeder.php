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
        $now = now();
        $pword = bcrypt('V67rNEXY');

        $super = Role::findOrFail(1);
        $admin = Role::findOrFail(2);
        $agent = Role::findOrFail(3);

        $dani = User::create(['username' => 'dani.xerosoft', 'email' => 'dani@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword, 'profile_picture' => 'Dani-5fa52fc9a848b.png']);
        $dani->assignRole($super);
        $dani->bio()->create(['first_name' => 'Dan', 'last_name' => 'Chris']);

        $jerome = User::create(['username' => 'jerome.xerosoft', 'email' => 'jerome@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword, 'profile_picture' => 'Jerome-5fa52fff8e24d.png']);
        $jerome->assignRole($agent);
        $jerome->bio()->create(['first_name' => 'Jerome', 'last_name' => 'Dymosco']);

        $alfredo = User::create(['username' => 'alfredo.xerosoft', 'email' => 'alfredo@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword, 'profile_picture' => 'Alfredo-5fa53018f0d38.jpeg']);
        $alfredo->assignRole($agent);
        $alfredo->bio()->create(['first_name' => 'Alfredo', 'last_name' => 'Flores']);

        $mia = User::create(['username' => 'mia.xerosoft', 'email' => 'mia@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword, 'profile_picture' => 'Mia-5fa5302a832df.png']);
        $mia->assignRole($agent);
        $mia->bio()->create(['first_name' => 'Mia', 'last_name' => 'Santos']);

        $jane = User::create(['username' => 'jane.xerosoft', 'email' => 'jane@xerosoft.com', 'email_verified_at' => $now, 'password' => $pword, 'profile_picture' => 'Jane-5fa5303aafe95.png']);
        $jane->assignRole($agent);
        $jane->bio()->create(['first_name' => 'Jane', 'last_name' => 'Descarga']);
    }
}
