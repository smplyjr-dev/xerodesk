<?php

namespace App\Models\Company;

use App\BaseModel;

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
}
