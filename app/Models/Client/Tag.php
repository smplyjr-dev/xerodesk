<?php

namespace App\Models\Client;

use App\BaseModel;
use App\Models\Taggable\Taggable;

class Tag extends BaseModel
{
    protected $table = 'client_session_taggable';

    public function taggables()
    {
        return $this->belongsTo(Taggable::class);
    }
}
