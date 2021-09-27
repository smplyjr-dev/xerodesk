<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Session;
use App\Traits\Client\SessionTagTrait;
use App\Traits\Client\SessionTrait;
use Exception;

class SessionController extends Controller
{
    use SessionTrait, SessionTagTrait;

    public function index()
    {
        $sessions = Session::all();

        return response()->json($sessions, 200);
    }

    public function store()
    {
        $model = Session::create([
            'client_id' => request()->client_id,
            'session'   => request()->session
        ]);

        return $model->fresh();
    }

    public function show($session)
    {
        $model = Session::whereSession($session)->firstOrFail();

        return response()->json($model, 200);
    }

    public function update($session)
    {
        verify_permission(auth()->user(), ['edit_ticket']);

        try {
            $model = Session::whereSession($session)->firstOrFail();

            $model->update(request()->only([
                'group_id',
                'user_id',
                'title',
                'category',
                'priority',
                'resolution_code',
                'solution',
                'token',
                'status',
            ]));

            return $model->fresh();
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function destroy($session)
    {
        $model = Session::where('session', $session)->firstOrFail();
        $model->delete();

        return true;
    }
}
