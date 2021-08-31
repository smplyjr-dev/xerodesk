<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Traits\Company\CompanyTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    use CompanyTrait;

    public function index()
    {
        $model = Company::all();

        return response()->json($model, 200);
    }

    public function store()
    {
        verify_permission(auth()->user(), ['create_company']);

        request()->validate([
            'name' => 'required',
            'url'  => 'required|active_url'
        ]);

        DB::beginTransaction();

        try {
            Company::create([
                'name' => request()->name,
                'url' => request()->url
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'A company has been successfully created.'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            throw ValidationException::withMessages([
                'general' => ['Something went wrong. Please contact the system administrator.'],
                'data' => [$e->getMessage()]
            ]);
        }
    }

    public function show($id)
    {
        $model = Company::findOrFail($id);

        return response()->json($model, 200);
    }

    public function update($id)
    {
        verify_permission(auth()->user(), ['edit_company']);

        $model = Company::findOrFail($id);
        $model->update(request()->all());

        return response()->json($model, 200);
    }

    public function destroy($id)
    {
        verify_permission(auth()->user(), ['delete_company']);

        // find and delete the record
        Company::findOrFail($id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'The company has been successfully deleted.'
        ], 200);
    }
}
