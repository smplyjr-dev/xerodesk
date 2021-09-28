<?php

namespace App\Traits\Client;

use App\Models\Client\Session;
use Exception;
use Illuminate\Support\Facades\DB;

trait TicketTrait
{
    public function datatable()
    {
        try {
            $columns = ['client', 'company', 'session', 'priority', 'group_id', 'agent_id', 'status', 'created_at'];

            $length = request()->length;
            $column = request()->column; // index
            $dir = request()->dir;
            $searchValue = request()->search;

            $query = Session::with('messages')
                ->join('clients AS c', 'client_sessions.client_id', '=', 'c.id')
                ->leftJoin('users AS u', 'client_sessions.user_id', '=', 'u.id')
                ->leftJoin('user_bio AS ub', 'u.id', '=', 'ub.user_id')
                ->select(
                    // main details
                    'client_sessions.id AS id',
                    'c.name AS client',
                    'client_sessions.title AS title',
                    'client_sessions.session AS session',
                    'client_sessions.priority AS priority',
                    'client_sessions.status AS status',
                    'client_sessions.group_id AS group_id',

                    // extra agent details
                    'u.id AS agent_id',
                    DB::raw("CONCAT(ub.first_name, ' ', ub.last_name) AS agent_name"),
                    'u.email AS agent_email',
                    'u.profile_picture AS agent_picture',

                    // extra client details
                    'c.email AS client_email',
                    'client_sessions.created_at AS created_at',
                    'client_sessions.updated_at AS updated_at'
                )
                ->orderBy($columns[$column], $dir);

            // if ($searchValue) {
            //     $query->where(function ($query) use ($searchValue) {
            //         $query->where('client_sessions.title', 'like', '%' . $searchValue . '%');
            //     });
            // }

            // from the refine search
            if (request()->company)  $query->where('c.company_id',             request()->company);
            if (request()->session)  $query->where('client_sessions.session',  request()->session);
            if (request()->priority) $query->where('client_sessions.priority', request()->priority);
            if (request()->agent)    $query->where('client_sessions.user_id',  request()->agent);
            if (request()->status)   $query->where('client_sessions.status',   request()->status);
            if (request()->created_at) {
                $time = strtotime(request()->created_at);
                $date = date('Y-m-d', $time);

                $query->whereDate('client_sessions.created_at', $date);
            }

            $tickets = $query->paginate($length);

            return ['data' => $tickets, 'draw' => request()->draw];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
