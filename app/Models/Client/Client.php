<?php

namespace App\Models\Client;

use App\BaseModel;
use App\Mail\ClientWelcome;
use App\Models\Company\Company;
use Illuminate\Support\Facades\Mail;

class Client extends BaseModel
{
    protected $table = 'clients';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function sendWelcomeMessageNotification()
    {
        Mail::to($this->email)->send(new ClientWelcome($this));
    }
}
