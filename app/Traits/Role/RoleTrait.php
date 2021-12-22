<?php

namespace App\Traits\Role;

use App\Models\Role\Role;

trait RoleTrait
{
    public function datatable()
    {
        $query = cache()->remember('roles-index', remember_for(5), function () {
            return Role::with('permissions')->whereNotIn('id', [1])
                ->get()
                ->map(function ($post) {
                    return [
                        'id'          => $post->id,
                        'name'        => $post->name,
                        'permissions' => $post->permissions->map(function ($permission) {
                            return [
                                'name' => $permission->name,
                                'slug' => $permission->slug
                            ];
                        })
                    ];
                })
                ->toArray();
        });

        return response()->json($query, 200);
    }
}
