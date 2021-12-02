<?php

namespace App\Traits\Client;

use App\Events\Session\SessionTransferFrom;
use App\Events\Session\SessionTransferTo;
use App\Exports\SessionsExport;
use App\Models\Client\Session;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

trait SessionTrait
{
    public function datatable()
    {
        try {
            $columns = ['sla', 'client', 'session', 'priority', 'group_id', 'agent_id', 'status', 'created_at'];

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
                    'client_sessions.category AS category',
                    'client_sessions.resolution_code AS resolution_code',
                    'client_sessions.solution AS solution',
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
            if (request()->session)  $query->where('client_sessions.session',  request()->session);
            if (request()->company)  $query->where('c.company_id',             request()->company);
            if (request()->priority) $query->where('client_sessions.priority', request()->priority);
            if (request()->agent)    $query->where('client_sessions.user_id',  request()->agent);
            if (request()->status)   $query->where('client_sessions.status',   request()->status);
            if (request()->created_at) {
                $time = strtotime(request()->created_at);
                $date = date('Y-m-d', $time);

                $query->whereDate('client_sessions.created_at', $date);
            }

            // using tap() to update the fields queried from paginate()
            $tickets = tap($query->paginate($length), function ($init) {
                return $init->getCollection()->transform(function ($item) {
                    // do your customization here
                    // $item->sla = 'Some value...';
                    return $item;
                });
            });

            return ['data' => $tickets, 'draw' => request()->draw];
        } catch (Exception $e) {
            return $e->getMessage();
        }
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

    public function transcript($session)
    {
        $session = Session::where('session', $session)->firstOrFail();
        $session->sendSessionMessagesNotification();

        return response()->json([
            'status'  => 'success',
            'message' => 'The transcript has been sent succesfully to the client email.',
        ], 200);
    }

    public function export()
    {
        $request   = json_decode(request('refine'), true);
        $extension = ucwords($request['type']);

        return Excel::download(new SessionsExport, 'sessions', $extension);
    }

    public function status($session)
    {
        $model = Session::where('session', $session)->firstOrFail();

        if ($model->status != request()->status) {
            $model->update([
                'status' => request()->status
            ]);

            $model->messages()->create([
                'hash'    => request()->hash,
                'sender'  => request()->sender,
                'message' => request()->message
            ]);
        }

        return $model;
    }

    public function seen($session)
    {
        $model = Session::with(['messages' => function ($query) {
            $query->where('is_read', false)
                ->whereIn('sender', ['client']);
        }]);
        $model = $model->where('session', $session);
        $model = $model->first();

        foreach ($model->messages as $message) {
            $message->update([
                'is_read' => true
            ]);
        }

        return $model;
    }

    public function lock($session)
    {
        $model = Session::where('session', $session)->firstOrFail();

        $model->update(request()->only([
            'user_id'
        ]));

        $model->sendSessionLockNotification();

        return $model;
    }

    public function field($session)
    {
        $model = Session::where('session', $session)->firstOrFail();

        $model->update(request()->only([
            'priority',
            'group_id',
        ]));

        return $model;
    }
}
