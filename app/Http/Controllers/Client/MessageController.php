<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Message;
use App\Models\Client\Session;
use App\Traits\Client\MessageTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    use MessageTrait;

    public function index()
    {
    }

    public function store()
    {
        DB::beginTransaction();

        try {
            $session = Session::where('session', request('session'))->firstOrFail();

            $message = Message::create([
                'session_id'   => $session->id,
                'user_id'      => request()->user_id,
                'hash'         => request()->hash,
                'sender'       => request()->sender,
                'message'      => request()->message,
                'reply_to'     => request()->reply_to
            ]);

            DB::commit();

            $response = [
                "status"  => "success",
                "message" => "Your chat conversation has been saved successfully.",
                "data"    => $message->fresh()
            ];

            return response()->json($response, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $response = [
                "status"        => "error",
                "message"       => "Something went wrong. Please contact your system administrator.",
                "error_code"    => $e->getCode(),
                "error_message" => $e->getMessage()
            ];

            return response()->json($response, 503);
        }
    }

    public function show($id)
    {
        $model = Message::findOrFail($id);
    }

    public function update($id)
    {
        $model = Message::findOrFail($id);
    }

    public function destroy($id)
    {
        $model = Message::findOrFail($id);
    }
}
