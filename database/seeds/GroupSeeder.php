<?php

use App\Models\Group\Group;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        // $group = Group::create(['name' => 'Billing', 'description' => 'Lorem ipsum dolor sit amet.']);
        // $group->users()->attach($users->filter(function ($query) {
        //     return in_array($query->id, [2, 5]);
        // }));

        $group = Group::create(['name' => 'Development', 'description' => 'Lorem ipsum dolor sit amet.']);
        $group->users()->attach($users->filter(function ($query) {
            return in_array($query->id, [2, 3]);
        }));

        $group = Group::create(['name' => 'Product Management', 'description' => 'Lorem ipsum dolor sit amet.']);
        $group->users()->attach($users->filter(function ($query) {
            return in_array($query->id, [1]);
        }));

        // $group = Group::create(['name' => 'QA', 'description' => 'Lorem ipsum dolor sit amet.']);
        // $group->users()->attach($users->filter(function ($query) {
        //     return in_array($query->id, [5]);
        // }));

        // $group = Group::create(['name' => 'Sales', 'description' => 'Lorem ipsum dolor sit amet.']);
        // $group->users()->attach($users->filter(function ($query) {
        //     return in_array($query->id, [4]);
        // }));
    }
}
