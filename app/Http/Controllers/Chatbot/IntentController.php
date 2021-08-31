<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\Intent;
use App\Traits\Chatbot\IntentTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class IntentController extends Controller
{
    use IntentTrait;

    public function index()
    {
    }

    public function store()
    {
        // validate the request
        request()->validate([
            'name' => 'required|max:50|alpha_underscore|unique:chatbot_intents'
        ]);

        DB::beginTransaction();

        try {
            $requests = request()->all();

            // save to our database
            $intent = Intent::createIntent($requests);

            DB::commit();

            $response = [
                'status'  => 'success',
                'message' => 'Your chatbot has been succesfully saved.',
                'data'    => $intent
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            DB::rollBack();

            $response = [
                'status'        => 'error',
                'message'       => 'Something went wrong. Please contact your system administrator.',
                'error_message' => $e->getMessage(),
                'error_code'    => $e->getCode()
            ];

            return response()->json($response, 400);
        }
    }

    public function show($id)
    {
    }

    public function update($id)
    {
        request()->validate([
            'utterances' => 'required|array',
            'responses'  => 'required|array'
        ], [
            'utterances.required' => 'You must atleast provide one utterance in order to trigger this intent.',
            'responses.required'  => 'You must atleast provide one response in order to fulfill this intent.'
        ]);

        DB::beginTransaction();

        try {
            $requests = request()->all();

            // update from our database
            $intent = Intent::updateIntent($requests, $id);

            DB::commit();

            $response = [
                'status'  => 'success',
                'message' => 'Your chatbot has been succesfully updated.',
                'data'    => $intent
            ];

            return response()->json($response, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $response = [
                'status'        => 'error',
                'message'       => 'Something went wrong. Please contact your system administrator.',
                'error_message' => $e->getMessage(),
                'error_code'    => $e->getCode()
            ];

            return response()->json($response, 400);
        }
    }

    public function destroy($id)
    {
        // soft delete intent from our database
        $intent = tap(Intent::find($id))->delete();

        $response = [
            'status'  => 'success',
            'message' => 'Your chatbot has been succesfully deleted.',
            'data'    => $intent
        ];

        return response()->json($response, 200);
    }
}
