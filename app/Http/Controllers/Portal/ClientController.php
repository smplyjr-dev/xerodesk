<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;

class ClientController extends Controller
{
    public function recent()
    {
        $model = Client::latest()->limit(5)->get();

        return $model;
    }
}
