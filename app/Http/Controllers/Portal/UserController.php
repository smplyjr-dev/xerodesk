<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\UserBio;
use App\Traits\User\UserTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use UserTrait;

    public function index()
    {
        $users = User::with(['bio'])->get();

        return response()->json($users, 200);
    }

    public function store()
    {
        verify_permission(auth()->user(), ['create_user']);

        request()->validate([
            'first_name' => 'required|max:255|string',
            'last_name'  => 'required|max:255|string',
            'username'   => 'required|max:255|unique:users',
            'email'      => 'required|max:255|unique:users|email',
            'password'   => 'required|min:8|confirmed',
            'role'       => 'required'
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'company_id'        => 1,
                'username'          => request()->username,
                'email'             => request()->email,
                'password'          => bcrypt(request()->password),
                'email_verified_at' => request()->skip_verification ? date(now()) : null,
            ]);

            $user->assignRole(request()->role);

            UserBio::create([
                'user_id'    => $user->id,
                'first_name' => request()->first_name,
                'last_name'  => request()->last_name
            ]);

            // send email verification if skip_verification is false
            if (!request()->skip_verification) $user->sendEmailVerificationNotification();

            DB::commit();

            return response()->json($user, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            throw ValidationException::withMessages([
                'general' => ['Something went wrong. Please contact the system administrator.'],
                'data' => [$e->getMessage()]
            ]);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        $data = [
            'id'              => $user->id,
            'username'        => $user->username,
            'email'           => $user->email,
            'profile_picture' => $user->profile_picture,
            'status'          => $user->status,
            'bio'             => $user->bio,
            'created_at'      => $user->created_at,
            'updated_at'      => $user->updated_at,
        ];

        if (!$user->roles->isEmpty()) {
            $data = array_merge($data, [
                'role'        => $user->role()->name,
                'permissions' => $user->role()->permissions
            ]);
        }

        return response()->json($data, 200);
    }

    public function update($id)
    {
        verify_permission(auth()->user(), ['edit_user']);

        $user = User::with('bio')->findOrFail($id);

        if (request()->method == 'profile') {
            $rules = [
                'first_name' => 'required|alpha_space',
                'last_name'  => 'required|alpha_space',
                'email'      => 'required|unique:users,email,' . $user->id . '|email',
                'username'   => 'required|unique:users,username,' . $user->id,
                'role'       => 'required',
                'status'     => 'required',
            ];

            request()->validate($rules);

            $user->update(request()->only([
                'first_name',
                'last_name',
                'email',
                'username',
                'role',
                'status',
            ]));

            // update user role
            $user->removeRole($user->role());
            $user->assignRole(request()->role);

            $user->bio->update(request()->only([
                'first_name',
                'last_name',
                'dob',
                'facebook',
                'twitter',
                'linkedin'
            ]));
        }

        if (request()->method == 'password') {
            request()->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:8|confirmed'
            ]);

            // check if old password is correct
            if (!Hash::check(request()->old_password, $user->password)) {
                throw ValidationException::withMessages([
                    'general' => 'Your old password did not match from your current password.'
                ]);
            }

            $user->update([
                'password' => bcrypt(request()->new_password)
            ]);
        }

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        verify_permission(auth()->user(), ['delete_user']);

        $user = User::with('bio')->findOrFail($id);
        $user->delete();

        return response()->json($user, 200);
    }
}
