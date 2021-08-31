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
            Company::create(['name' => 'FiliPay',              'url' => 'http://payroll-hr-leave-app.test', 'allowed_users' => [3, 7]]);
            Company::create(['name' => 'INS1',                 'url' => 'http://ins1.filipayroll.test',     'allowed_users' => []]);
            Company::create(['name' => 'INS2',                 'url' => 'http://ins2.filipayroll.test',     'allowed_users' => []]);
        } else {
            Company::create(['name' => 'Demo',                 'url' => 'https://demo.filipayroll.com',             'allowed_users' => []]);
            Company::create(['name' => 'BOD',                  'url' => 'https://bod.filipayroll.com',              'allowed_users' => []]);
            Company::create(['name' => 'WTECH',                'url' => 'https://wtech.filipayroll.com',            'allowed_users' => []]);
            Company::create(['name' => 'XMC BPO',              'url' => 'https://xerosoft.filipayroll.com',         'allowed_users' => []]);
            Company::create(['name' => 'VIRTUS',               'url' => 'https://virtus.filipayroll.com',           'allowed_users' => []]);
            Company::create(['name' => 'Top Creamery',         'url' => 'https://topcreamery.filipayroll.com',      'allowed_users' => []]);
            Company::create(['name' => 'The Tile Gallery',     'url' => 'https://tilegallery.filipayroll.com',      'allowed_users' => []]);
            Company::create(['name' => 'ASIANPREMIER',         'url' => 'https://asiapremier.filipayroll.com',      'allowed_users' => []]);
            Company::create(['name' => 'SMARTPROBE',           'url' => 'https://smartprobe.filipayroll.com',       'allowed_users' => []]);
            Company::create(['name' => 'LITEXPRESS',           'url' => 'https://litexpress.filipayroll.com',       'allowed_users' => []]);
            Company::create(['name' => 'Golden Star & Manfel', 'url' => 'https://manfelgoldenstar.filipayroll.com', 'allowed_users' => []]);
            Company::create(['name' => 'Metrowaste',           'url' => 'https://metrowaste.filipayroll.com',       'allowed_users' => []]);
            Company::create(['name' => 'Creedon',              'url' => 'https://nixplay.filipayroll.com',          'allowed_users' => []]);
            Company::create(['name' => 'Rakso',                'url' => 'https://raksoct.filipayroll.com',          'allowed_users' => []]);
        }
    }
}
