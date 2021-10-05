<?php

namespace App\Traits\Client;

use App\Models\Client\Client;
use App\Models\Client\Session;
use Exception;
use Illuminate\Support\Facades\DB;

trait ClientTrait
{
    public function datatable()
    {
        try {
            $columns = ['name', 'email', 'token', 'employee_id', 'phone'];

            $length = request()->length;
            $column = request()->column; // index
            $dir = request()->dir;
            $searchValue = request()->search;

            $query = DB::table('clients AS c')
                ->select(
                    'c.id AS id',
                    'c.name AS name',
                    'c.email AS email',
                    'c.token AS token',
                    'c.employee_id AS employee_id',
                    'c.phone AS phone'
                )
                ->orderBy($columns[$column], $dir);

            if ($searchValue) {
                $query->where(function ($query) use ($searchValue) {
                    $query->where('c.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('c.token', 'like', '%' . $searchValue . '%')
                        ->orWhere('c.email', 'like', '%' . $searchValue . '%');
                });
            }

            $clients = $query->paginate($length);

            return ['data' => $clients, 'draw' => request()->draw];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function verify($token)
    {
        $response = [];
        $cookie   = encrypter('decrypt', request()->cookie);
        $client   = Client::with(['sessions.messages'])->whereToken($token)->first();

        if ($client) {
            // let's check if the token from your storage belongs to you by email
            if ($client->email == $cookie['user_email']) {
                // let's update the client, they maybe change email or picture already
                $client->update([
                    'email'   => $cookie['user_email'],
                    'name'    => $cookie['user_name'],
                    'phone'   => $cookie['user_phone'],
                    'picture' => $cookie['user_picture']
                ]);

                $response = [
                    "status"  => "success",
                    "message" => "Success! Check the data property.",
                    "data"    => $client
                ];
            } else {
                $model = Client::with(['sessions.messages'])->whereEmail($cookie['user_email'])->first();

                if ($model) {
                    // let's update the client, they maybe change email or picture already
                    $model->update([
                        'email'   => $cookie['user_email'],
                        'name'    => $cookie['user_name'],
                        'phone'   => $cookie['user_phone'],
                        'picture' => $cookie['user_picture']
                    ]);

                    $response = [
                        "status"  => "success",
                        "message" => "Success! Check the data property.",
                        "data"    => $model
                    ];
                } else {
                    return $this->store();
                }
            }
        } else {
            return $this->store();
        }

        return response()->json($response, 200);
    }

    public function attachments($id)
    {
        $tickets = Session::where('client_id', $id)->get();
        $attachments = [];

        return $attachments;
    }

    public function sessions($token)
    {
        $sessions = Client::with('sessions.messages.attachments')
            ->where('token', $token)
            ->first();

        return $sessions;
    }
}
