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
            $columns = ['name', 'email', 'phone'];

            $length = request()->length;
            $column = request()->column; // index
            $dir = request()->dir;
            $searchValue = request()->search;

            $query = DB::table('clients AS c')
                ->select(
                    'c.id AS id',
                    'c.name AS name',
                    'c.token AS token',
                    'c.email AS email',
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
