<?php

namespace App\Models\Company;

use App\BaseModel;
use App\Models\Client\Client;
use App\Models\User\User;

class Company extends BaseModel
{
    protected $casts = [
        'allowed_users' => 'array'
    ];

    protected $attributes = [
        'allowed_users' => '[]'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
