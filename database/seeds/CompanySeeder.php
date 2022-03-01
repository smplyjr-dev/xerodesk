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
        Company::create([
            'name' => 'Xerodesk Widget',
            'url'  => 'http://xerodesk-widget.test',
            'abbr' => 'XW',
        ]);
    }
}
