<?php

namespace App\Models\Taggable;

use App\BaseModel;
use App\Models\Client\Session;

class Taggable extends BaseModel
{
    protected $table = 'taggables';

    public function sessions()
    {
        return $this->belongsToMany(Session::class)->withTimestamps();
    }
}
