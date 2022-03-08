<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Traits\Company\CompanyTrait;

class CompanyController extends Controller
{
    use CompanyTrait;

    public function index()
    {
        $model = Company::all();

        return response()->json($model, 200);
    }

    public function show($company)
    {
        $model = Company::findOrFail($company);

        return $model;
    }

    public function update($company)
    {
        $model = Company::findOrFail($company);
        $model->update(request()->only(['name', 'url']));

        return $model;
    }
}
