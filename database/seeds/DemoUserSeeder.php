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
        $now    = now();
        $pword  = bcrypt('V67rNEXY');
        $role   = Role::findOrFail(2);
        $admins = [
            ['first_name' => 'Maria Karina', 'middle_name' => 'Sarzuela', 'last_name' => 'Obispo',   'username' => 'maria.xerodesk',   'email' => 'karina.obispo@xmcbpo.com',  'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Chuck',        'middle_name' => null,       'last_name' => 'Caberoy',  'username' => 'chuck.xerodesk',   'email' => 'chuck.caberoy@xmcbpo.com',  'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Edwin',        'middle_name' => null,       'last_name' => 'Valbuena', 'username' => 'edwin.xerodesk',   'email' => 'edwin.valbuena@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Allan Louie',  'middle_name' => 'Cosico',   'last_name' => 'Honrales', 'username' => 'allan.xerodesk',   'email' => 'allan.honrales@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Henry',        'middle_name' => 'Cenina',   'last_name' => 'Aragones', 'username' => 'henry.xerodesk',   'email' => 'henry.aragones@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Gilbert',      'middle_name' => 'Gunay',    'last_name' => 'Campo',    'username' => 'gilbert.xerodesk', 'email' => 'gilbert.campo@xmcbpo.com',  'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Jasper',       'middle_name' => 'Ong',      'last_name' => 'Mariano',  'username' => 'jasper.xerodesk',  'email' => 'jasper.mariano@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        ];

        foreach ($admins as $admin) {
            $u = User::create(['company_id' => 1, 'username' => $admin['username'], 'email' => $admin['email'], 'email_verified_at' => $now, 'password' => $pword]);
            $u->assignRole($role);
            $u->bio()->create(['first_name' => $admin['first_name'], 'last_name' => $admin['last_name']]);
        }
    }
}
