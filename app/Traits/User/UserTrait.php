<?php

namespace App\Traits\User;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait UserTrait
{
    public function datatable()
    {
        $query = User::with('bio:id,user_id,first_name,last_name,facebook,twitter,linkedin');
        $query->whereNotIn('id', [1]);

        if (request()->date) $query->whereDate('created_at', Carbon::create(request()->date));

        $users = [];

        foreach ($query->get() as $key => $record) {
            $name  = $record->bio->last_name . ', ' . $record->bio->first_name;

            $users[$key] = [
                'id'                => $record->id,
                'name'              => $name,
                'role'              => $record->role()->name,
                'status'            => $record->status == true ? 'Activated' : 'Deactivated',
                'email_verified_at' => $record->email_verified_at,
                'meta'  => [
                    'bio'             => $record->bio,
                    'email'           => $record->email,
                    'profile_picture' => $record->profile_picture
                ]
            ];
        }

        return $users;
    }

    public function me()
    {
        $auth = request()->user();
        $user = [
            'id'              => $auth->id,
            'company_id'      => $auth->company_id,
            'username'        => $auth->username,
            'email'           => $auth->email,
            'profile_picture' => $auth->profile_picture,
            'status'          => $auth->status,
            'bio'             => $auth->bio,
            'created_at'      => $auth->created_at,
            'updated_at'      => $auth->updated_at,
        ];

        if ($auth->roles->isEmpty()) {
            $user = array_merge($user, [
                'role'        => '',
                'permissions' => []
            ]);
        } else {
            $user = array_merge($user, [
                'role'        => $auth->role()->name,
                'permissions' => $auth->role()->permissions
            ]);
        }

        return response()->json($user, 200);
    }

    public function picture()
    {
        request()->validate([
            'image' => 'required|image64:jpeg,jpg,png',
            'size'  => 'numeric|max:3000000', // 3mb in bytes
        ], [
            'size.max' => 'The file size should not be greater than 3MB.',
        ]);

        $id    = request()->id;
        $user  = User::findOrFail($id);
        $image = request()->image;
        $name  = request()->name;
        $old   = request()->old;

        $file = storage_path("app\\public\\uploads\\users\\$id\\$old");

        // remove the previous profile picture
        if (file_exists($file)) unlink($file);

        $exploded  = explode(',', $image);
        $extension = explode(';', explode('/', $exploded[0])[1])[0];
        $filename  = $name . '-' . uniqid() . '.' . $extension;
        $decoded   = base64_decode($exploded[1]);
        $path      = "public\\uploads\\users\\$id\\$filename";

        // save to storage directory
        Storage::put($path, $decoded);

        // save to user model
        $user->update([
            'profile_picture' => $filename
        ]);

        return response()->json($user->profile_picture, 200);
    }
}
