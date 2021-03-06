<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client\Session;
use App\Traits\Client\SessionTagTrait;
use App\Traits\Client\SessionTrait;
use Exception;
use Spatie\QueryBuilder\QueryBuilder;

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
            'session'   => request()->session,
            'status'    => 0
        ]);

        return $model->fresh();
    }

    public function show($session)
    {
        $model = QueryBuilder::for(Session::class)
            ->allowedIncludes('client.company', 'messages.attachments', 'taggables', 'user.bio')
            ->where('session', $session)
            ->firstOrFail();

        return $model;
    }

    public function update($session)
    {
        verify_permission(auth()->user(), ['edit_ticket']);

        try {
            $model = Session::whereSession($session)->firstOrFail();
            $model_temp = $model->replicate()->setRawAttributes($model->getOriginal());
            $model->update(request()->only([
                'title',
                'priority',
                'category',
                'resolution_code',
                'solution',
                'status',
            ]));
            $model->logger('update');
            $model->sendSessionUpdateNotification();

            if ($model_temp->status != request()->status) {
                $model->messages()->create([
                    'user_id' => request()->user_id,
                    'hash'    => request()->hash,
                    'sender'  => request()->sender,
                    'message' => request()->message
                ]);
            }

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
