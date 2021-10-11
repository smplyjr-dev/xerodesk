<?php

use App\Models\Company\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') == 'local') {
            Company::create([
                'name'    => 'FiliPay',
                'address' => 'Unit 404, 4F Ortigas Technopoint Two, Doña Julia Vargas Ave, Ortigas Center, Pasig, Metro Manila',
                'url'     => 'http://xmcit-widget.test',
                'abbr'    => 'FP',
            ]);
        } else {
            Company::create([
                'name'    => 'XMC IT',
                'address' => 'Unit 404, 4F Ortigas Technopoint Two, Doña Julia Vargas Ave, Ortigas Center, Pasig, Metro Manila',
                'url'     => 'https://xmcit.cloudx30.com',
                'abbr'    => 'XMCIT',
            ]);
        }
    }
}
