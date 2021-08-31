<?php

namespace App\Traits\Role;

use App\Models\Role\Role;

trait RoleTrait
{
    public function datatable()
    {
        $query = Role::with('permissions');
        $query->whereNotIn('id', [1]);

        return $query->get();
    }
}
