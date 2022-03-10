<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\UserReply;

class UserReplyController extends Controller
{
    public function store()
    {
        request()->validate([
            'name' => 'required|unique:user_replies',
            'body' => 'required',
        ]);

        $query = new UserReply();
        $query->user_id = request()->user()->id;
        $query->name = request()->name;
        $query->body = request()->body;
        $query->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'The role has been successfully created.'
        ], 200);
    }

    public function update($id)
    {
        $reply = UserReply::findOrFail($id);

        request()->validate([
            'name' => 'required|unique:users,email,' . $reply->id,
            'body' => 'required',
        ]);

        $reply->name = request()->name;
        $reply->body = request()->body;
        $reply->update();

        return response()->json([
            'status'  => 'success',
            'message' => 'The role has been successfully updated.'
        ], 200);
    }

    public function destroy($id)
    {
        $reply = UserReply::findOrFail($id);
        $reply->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'The role has been successfully deleted.'
        ], 200);
    }
}
