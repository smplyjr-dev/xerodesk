<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

trait Logger
{
    protected static $old;

    public static function bootLogger()
    {
        static::updating(function (Model $model) {
            static::$old = $model
                ->replicate()
                ->setRawAttributes($model->getOriginal())
                ->toArray();
        });
    }

    public function logs()
    {
        return Activity::whereSubjectType(get_class())
            ->whereSubjectId($this->id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
