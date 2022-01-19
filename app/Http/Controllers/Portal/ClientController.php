<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Traits\Client\ClientTrait;

class ClientController extends Controller
{
    use ClientTrait;

    public function index()
    {
        $query = Client::all();

        return $query;
    }
}
