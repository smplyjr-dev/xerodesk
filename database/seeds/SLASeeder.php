<?php

use App\Models\SLA\SLA;
use Illuminate\Database\Seeder;

class SLASeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SLA::create(['name' => 'Black (15 days and beyond)', 'color' => 'black',  'range' => 15]);
        SLA::create(['name' => 'Red (3-15 days)',            'color' => 'red',    'range' => 14]);
        SLA::create(['name' => 'Yellow (2-3 days)',          'color' => 'yellow', 'range' => 3]);
        SLA::create(['name' => 'Green (Less than 2 days)',   'color' => 'green',  'range' => 2]);
    }
}
