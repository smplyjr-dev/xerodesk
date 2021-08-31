<?php

namespace App\Traits\Company;

use App\Models\Company\Company;
use GuzzleHttp\Client;

trait CompanyTrait
{
    public function datatable()
    {
        return Company::all();
    }

    public function verify()
    {
        $cookie  = encrypter('decrypt', request()->cookie);
        $company = Company::whereUrl($cookie['company_url'])->firstOrFail();

        if (in_array($cookie['employee_id'], $company->allowed_users)) {
            return response()->json(true, 200);
        }

        return response()->json(false, 200);
    }

    public function users()
    {
        $client   = new Client();
        $company  = Company::findOrFail(request()->company_id);
        $response = [];
        $url      = $company->url . '/api/users?key=' . env('LCS_API_KEY');

        $query    = $client->get($url);
        $response = [
            'status'  => 'success',
            'message' => 'A list of all users.',
            'users'   => json_decode($query->getBody(), true)
        ];

        // $curl_init = curl_init();
        // curl_setopt($curl_init, CURLOPT_URL, $url);
        // curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl_init, CURLOPT_HTTPGET, 1);
        // curl_setopt($curl_init, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl_init, CURLOPT_SSL_VERIFYPEER, false);

        // $query = curl_exec($curl_init);

        // if (curl_error($curl_init)) {
        //     $response = [
        //         'status'  => 'error',
        //         'message' => curl_error($curl_init)
        //     ];
        // } else {
        //     $response = json_decode($query, true);
        // }

        return response()->json($response, 200);
    }
}
