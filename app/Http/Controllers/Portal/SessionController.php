<?php

namespace App\Http\Controllers\Portal;

use App\Exports\SessionsExport;
use App\Http\Controllers\Controller;
use App\Models\Client\Session;
use Maatwebsite\Excel\Facades\Excel;

class SessionController extends Controller
{
    public function show($session)
    {
        $model = Session::with(['taggables', 'client.company', 'messages.attachments', 'user.bio'])
            ->where('session', $session)
            ->first();

        return $model;
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
}
