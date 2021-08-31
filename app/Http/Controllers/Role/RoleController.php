<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role\Permission;
use App\Models\Role\Role;
use App\Traits\Role\RoleTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    use RoleTrait;

    public function index()
    {
        $query = Role::query();
        $query->where('id', '!=', 1);

        return response()->json($query->get(), 200);
    }

    public function store()
    {
        verify_permission(auth()->user(), ['create_role']);

        request()->validate([
            'name'        => 'required|unique:roles',
            'permissions' => 'required|array'
        ]);

        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => request()->name
            ]);

            foreach (request()->permissions as $p) {
                $role->givePermissionTo($p['name']);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'A role has been successfully created.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            throw ValidationException::withMessages([
                'general' => ['Something went wrong. Please contact the system administrator.'],
                'data' => [$e->getMessage()]
            ]);
        }
    }

    public function show($id)
    {
    }

    public function update($id)
    {
        verify_permission(auth()->user(), ['edit_role']);

        request()->validate([
            'name'        => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array'
        ]);

        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);
            $permissions = Permission::all();

            // update role
            $role->name = request()->name;
            $role->save();

            // delete all exisiting permissions
            foreach ($permissions as $p) {
                $role->revokePermissionTo($p->name);
            }

            // add all new permissions
            foreach (request()->permissions as $p) {
                // create and attach new permission to role
                $exist = $permissions->firstWhere('name', $p['name']);
                if (is_null($exist)) {
                    $created = Permission::create($p);
                    $created->assignRole('Super'); // attach to super admin role
                }

                $role->givePermissionTo($p['name']);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'The role has been successfully updated.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            throw ValidationException::withMessages([
                'general' => ['Something went wrong. Please contact the system administrator.'],
                'data' => [$e->getMessage()]
            ]);
        }
    }

    public function destroy($id)
    {
        verify_permission(auth()->user(), ['delete_role']);

        $role = Role::findOrFail($id);

        if ($role->users->isEmpty()) {
            $role->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'The role has been successfully deleted.'
            ], 200);
        } else {
            return response()->json([
                'status'  => 'danger',
                'message' => 'The role are being used by users.'
            ], 200);
        }
    }
}
