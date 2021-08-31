<?php

namespace App\Http\Controllers\SLA;

use App\Http\Controllers\Controller;
use App\Models\SLA\SLA;
use App\Traits\Client\SLATrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SLAController extends Controller
{
    use SLATrait;

    public function index()
    {
        $model = SLA::all();

        return response()->json($model, 200);
    }

    public function store()
    {
        verify_permission(auth()->user(), ['create_sla']);

        request()->validate([
            'name'  => 'required|unique:slas',
            'color' => 'required|unique:slas',
            'range' => 'required|unique:slas|numeric|max:100',
        ]);

        DB::beginTransaction();

        try {
            SLA::create([
                'name'  => request()->name,
                'color' => request()->color,
                'range' => request()->range,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'A SLA has been successfully created.'
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
        verify_permission(auth()->user(), ['edit_sla']);

        request()->validate([
            'name'  => 'required|unique:slas,name,' . $id,
            'color' => 'required|unique:slas,color,' . $id,
            'range' => 'required|unique:slas,range,' . $id . '|numeric|max:100',
        ]);

        DB::beginTransaction();

        try {
            $sla = SLA::findOrFail($id);

            // update the sla
            $sla->name  = request()->name;
            $sla->color = request()->color;
            $sla->range = request()->range;
            $sla->save();

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'The SLA has been successfully updated.'
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
        verify_permission(auth()->user(), ['delete_sla']);

        $sla = SLA::findOrFail($id);
        $sla->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'The SLA has been successfully deleted.'
        ], 200);
    }
}
