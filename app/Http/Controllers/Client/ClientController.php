<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Traits\Client\ClientTrait;
use App\Models\Company\Company;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    use ClientTrait;

    public function index()
    {
        $clients = Client::all();

        return response()->json($clients, 200);
    }

    public function store()
    {
        DB::beginTransaction();

        try {
            $cookie = encrypter('decrypt', request()->cookie);

            // create the company, if not exist
            $company = Company::firstOrCreate([
                'url'  => $cookie['company_url']
            ], [
                'name' => $cookie['company_name'],
                'url'  => $cookie['company_url']
            ]);

            // create the client, if not exist
            $client = Client::with(['sessions.messages.attachments'])->firstOrCreate([
                'email'      => $cookie['user_email']
            ], [
                'company_id' => $company->id,
                'token'      => Str::random(25),
                'email'      => $cookie['user_email'],
                'name'       => $cookie['user_name'],
                'phone'      => $cookie['user_phone'],
                'picture'    => $cookie['user_picture'],
            ]);

            DB::commit();

            $response = [
                "status"  => "success",
                "message" => "A new client has been created.",
                "data"    => $client
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            DB::rollBack();

            $response = [
                "status"        => "error",
                "message"       => "Something went wrong. Please contact your system administrator.",
                "error_code"    => $e->getCode(),
                "error_message" => $e->getMessage()
            ];

            return response()->json($response, 503);
        }
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);

        return response()->json($client, 200);
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
