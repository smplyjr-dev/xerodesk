<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group\Group;
use App\Traits\Group\GroupTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    use GroupTrait;

    public function index()
    {
        $groups = Group::all();

        return response()->json($groups, 200);
    }

    public function store()
    {
        verify_permission(auth()->user(), ['create_group']);

        request()->validate([
            'name' => 'required|unique:groups'
        ]);

        DB::beginTransaction();

        try {
            Group::create([
                'name'        => request()->name,
                'description' => request()->description
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'A group has been successfully created.'
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
        verify_permission(auth()->user(), ['edit_group']);

        request()->validate([
            'name' => 'required|unique:groups,name,' . $id
        ]);

        DB::beginTransaction();

        try {
            $group = Group::findOrFail($id);

            // update the group
            $group->name        = request()->name;
            $group->description = request()->description;
            $group->save();

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'The group has been successfully updated.'
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
        verify_permission(auth()->user(), ['delete_group']);

        $group = Group::findOrFail($id);

        if ($group->users->isEmpty()) {
            $group->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'The group has been successfully deleted.'
            ], 200);
        } else {
            return response()->json([
                'status'  => 'danger',
                'message' => 'The group are being used by users.'
            ], 200);
        }
    }
}
