<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Group\GroupUser;

class GroupUserController extends Controller
{
    public function store()
    {
        $group = GroupUser::create(request()->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'The user has been successfully added to the group.',
            'data'    => $group
        ], 200);
    }

    public function destroy($id)
    {
        $user = GroupUser::where([
            ['group_id', request()->group_id],
            ['user_id',  $id]
        ])->first();

        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'The user has been successfully deleted from the group.'
        ], 200);
    }
}
