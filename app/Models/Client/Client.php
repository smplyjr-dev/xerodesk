<?php

namespace App\Models\Client;

use App\BaseModel;
use App\Models\Company\Company;

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
}
