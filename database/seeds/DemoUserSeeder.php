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

        $admin_role = Role::findOrFail(2);
        $admins = [
            ['first_name' => 'Maria Karina', 'middle_name' => 'Sarzuela', 'last_name' => 'Obispo', 'email' => 'karina.obispo@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Chuck', 'middle_name' => null, 'last_name' => 'Caberoy', 'email' => 'chuck.caberoy@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Edwin', 'middle_name' => null, 'last_name' => 'Valbuena', 'email' => 'edwin.valbuena@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Allan Louie', 'middle_name' => 'Cosico', 'last_name' => 'Honrales', 'email' => 'allan.honrales@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Henry', 'middle_name' => 'Cenina', 'last_name' => 'Aragones', 'email' => 'henry.aragones@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Gilbert', 'middle_name' => 'Gunay', 'last_name' => 'Campo', 'email' => 'gilbert.campo@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
            ['first_name' => 'Jasper', 'middle_name' => 'Ong', 'last_name' => 'Mariano', 'email' => 'jasper.mariano@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        ];
        foreach ($admins as $admin) {
            $u = User::create(['company_id' => 1, 'email' => $admin['email'], 'email_verified_at' => $now, 'password' => $pword]);
            $u->assignRole($admin_role);
            $u->bio()->create(['first_name' => $admin['first_name'], 'last_name' => $admin['last_name']]);
        }

        // $agent_role = Role::findOrFail(3);
        // $agents = [
        //     ['first_name' => 'EDUARDO', 'middle_name' => 'ABES', 'last_name' => 'ASTILLAZO', 'email' => 'ed.hagensen@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Richmond Ray', 'middle_name' => 'G', 'last_name' => 'Vergara', 'email' => 'richmond.vergara@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Dan', 'middle_name' => 'Brigatay', 'last_name' => 'Rico', 'email' => 'merimar.rico@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Haide Jean Mar', 'middle_name' => 'H', 'last_name' => 'Cahipe', 'email' => 'haide.cahipe@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Renie', 'middle_name' => null, 'last_name' => 'Guiriba', 'email' => 'neng.guiriba@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Katherine', 'middle_name' => 'Malvas', 'last_name' => 'Garcia', 'email' => 'katherine.garcia@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Maria Gracia', 'middle_name' => 'Alferez', 'last_name' => 'Valencia', 'email' => 'mariagracia.valencia@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Mary Jean', 'middle_name' => null, 'last_name' => 'Garcera', 'email' => 'Jean.Garcera@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Justine Moira', 'middle_name' => 'Guanco', 'last_name' => 'Golla', 'email' => 'justine.golla@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Jimmy Jr. ', 'middle_name' => 'Huyerra', 'last_name' => 'Lecciones', 'email' => 'jimmy.lecciones@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Sylvester', 'middle_name' => 'Pumares', 'last_name' => 'Agunias', 'email' => 'sylvester.agunias@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Leilani', 'middle_name' => 'Padillo', 'last_name' => 'Unigo', 'email' => 'leilani.unigo@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Armand', 'middle_name' => 'Blas', 'last_name' => 'Non', 'email' => 'armand.non@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Alvefons', 'middle_name' => 'Tobalado', 'last_name' => 'Busico', 'email' => 'alvefons.busico@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Ryan', 'middle_name' => 'Mariano', 'last_name' => 'Guzman', 'email' => 'ryan.guzman@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Raquel', 'middle_name' => 'Bosas', 'last_name' => 'Manglicmot', 'email' => 'raquel.manglicmot@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Shella', 'middle_name' => 'Gauran', 'last_name' => 'Lo', 'email' => 'shella.lo@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Sean Gilmary', 'middle_name' => 'Daantos', 'last_name' => 'Gantala', 'email' => 'sean.gantala@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'June Rey', 'middle_name' => '', 'last_name' => 'Suerte', 'email' => 'june.suerte@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Chielo', 'middle_name' => 'Estrada', 'last_name' => 'Bendoy', 'email' => 'chielo.bendoy@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Vines', 'middle_name' => 'Basallo', 'last_name' => 'Gonzalez', 'email' => 'vines.gonzalez@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Ma. Aileen', 'middle_name' => 'Cafino', 'last_name' => 'Alaban', 'email' => 'aileen.alaban@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'April Lou', 'middle_name' => 'Dagohoy', 'last_name' => 'Bulanon', 'email' => 'lou.bulanon@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Jaime', 'middle_name' => '', 'last_name' => 'Pregoner', 'email' => 'jaime.pregoner@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Angelie', 'middle_name' => 'Palmero', 'last_name' => 'Quimada', 'email' => 'angeliequimada@yahoo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Daryl', 'middle_name' => 'Bayla', 'last_name' => 'De Torres', 'email' => 'daryl.detorres@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Michelle Irish', 'middle_name' => 'Baring', 'last_name' => 'Khu', 'email' => 'michelle.khu@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Frances Ramon', 'middle_name' => 'Diez', 'last_name' => 'Amay', 'email' => 'abie.bernardez@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Jeramie', 'middle_name' => 'Mangoda', 'last_name' => 'Dulcero', 'email' => 'jeramie.dulcero@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Mary Mae', 'middle_name' => 'Labra', 'last_name' => 'Reyes', 'email' => 'mary.reyes@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'April Mae', 'middle_name' => 'Espantaleon', 'last_name' => 'Villahermosa', 'email' => 'april.villahermosa@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Archie', 'middle_name' => 'Comedido', 'last_name' => 'Yunson', 'email' => 'archie.yunson@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'April Rose', 'middle_name' => 'Librero', 'last_name' => 'Balagtas', 'email' => 'april.balagtas@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Jerome', 'middle_name' => 'Regencia', 'last_name' => 'Teston', 'email' => 'jerome.teston@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Cynthia', 'middle_name' => 'Tabilog', 'last_name' => 'Santos', 'email' => 'cynthia.santos@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Jeffrey', 'middle_name' => 'Garcia', 'last_name' => 'De Guzman', 'email' => 'jeffrey.deguzman@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Katrina Mae', 'middle_name' => '', 'last_name' => 'Ramirez', 'email' => 'katrina.ramirez@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Ariann', 'middle_name' => 'Alcoba', 'last_name' => 'Manrique', 'email' => 'ariann.manrique@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Lara Melissa', 'middle_name' => '', 'last_name' => 'Danao', 'email' => 'lara@xmcasia.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Kimberly', 'middle_name' => '', 'last_name' => 'Lirio', 'email' => 'xmcfinance2@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Catherine', 'middle_name' => '', 'last_name' => 'Castillo', 'email' => 'catherine.castillo@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        //     ['first_name' => 'Danilo', 'middle_name' => '', 'last_name' => 'Vergara', 'email' => 'xmcfinance@xmcbpo.com', 'email_verified_at' => $now, 'password' => $pword],
        // ];
        // foreach ($agents as $agent) {
        //     $u = User::create(['company_id' => 1, 'email' => $agent['email'], 'email_verified_at' => $now, 'password' => $pword]);
        //     $u->assignRole($agent_role);
        //     $u->bio()->create(['first_name' => $agent['first_name'], 'last_name' => $agent['last_name']]);
        // }
    }
}
