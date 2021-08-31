<?php

namespace App\Traits\Group;

use App\Models\Group\Group;
use App\Models\User\User;
use Carbon\Carbon;

trait GroupTrait
{
    public function datatable()
    {
        if (request()->for_user) return $this->users();
        else return $this->groups();
    }

    public function groups()
    {
        $query = Group::with('users:profile_picture');

        return $query->get();
    }

    public function users()
    {
        $query = User::with('bio:id,user_id,first_name,last_name');
        $query->whereNotIn('id', [1]);

        if (request()->date) $query->whereDate('created_at', Carbon::create(request()->date));

        $users = [];

        foreach ($query->get() as $key => $record) {
            $name  = $record->bio->last_name . ', ' . $record->bio->first_name;

            $users[$key] = [
                'id'   => $record->id,
                'name' => $name,
                'meta' => [
                    'email'           => $record->email,
                    'profile_picture' => $record->profile_picture
                ]
            ];
        }

        return $users;
    }
}
