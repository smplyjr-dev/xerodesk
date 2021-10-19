<?php

namespace App\Http\Controllers\Plugin;

use App\Http\Controllers\Controller;
use App\Models\Client\Session;
use App\Models\Company\Company;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function messages($session)
    {
        $model = Session::with('messages', 'messages.attachments');
        $model = $model->where('session', $session);
        $model = $model->firstOrFail();

        return $model;
    }

    public function seen($session)
    {
        $model = Session::with(['messages' => function ($query) {
            $query->where('is_read', false)
                ->whereIn('sender', ['admin', 'session']);
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

    public function store()
    {
        $abbr  = Company::first()->abbr;
        $count = Session::whereDate('created_at', Carbon::today()->toDateString())->count();
        $model = Session::create([
            'client_id' => request()->client_id,
            'session'   => 'XD' . $abbr . date('ymd') . ($count + 1)
        ]);

        return $model->fresh();
    }
}
