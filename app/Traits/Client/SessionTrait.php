<?php

namespace App\Traits\Client;

use App\Events\Session\SessionTransferFrom;
use App\Events\Session\SessionTransferTo;
use App\Models\Client\Session;
use Exception;
use Illuminate\Support\Facades\DB;

trait SessionTrait
{
    public function clients()
    {
        $clients = Session::query();
        $clients = $clients->with('client');
        $clients = $clients->with('client.company');
        $clients = $clients->with('client.sessions');
        $clients = $clients->with('client.sessions.messages');
        $clients = $clients->with('client.sessions.taggables');
        $clients = $clients->groupBy('client_id');
        $clients = $clients->orderByDesc('created_at');
        $clients = $clients->get();

        return response()->json($clients, 200);
    }

    public function transfer($session)
    {
        DB::beginTransaction();

        try {
            $session = Session::whereSession($session)->first();
            $session->update(['user_id' => request()->new_user_id]);

            SessionTransferFrom::dispatch(request()->old_user_id, $session->session);
            SessionTransferTo::dispatch(request()->new_user_id, $session->session);

            DB::commit();

            $response = [
                'status'  => 'success',
                'message' => 'The session has been succesfully transferred.',
                'data'    => $session
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $response = [
                'status'        => 'error',
                'message'       => 'Something went wrong. Please contact your system administrator.',
                'error_message' => $e->getMessage(),
                'error_code'    => $e->getCode()
            ];
        }

        return response()->json($response, 200);
    }

    public function messages($session)
    {
        $session = Session::with(['messages', 'messages.attachments'])
            ->whereSession($session)
            ->first();

        return response()->json($session->messages, 200);
    }

    public function transcript($session)
    {
        $session = Session::where('session', $session)->firstOrFail();
        $session->sendSessionMessagesNotification();

        return response()->json([
            'status'  => 'success',
            'message' => 'The transcript has been sent succesfully to the client email.',
        ], 200);
    }
}
