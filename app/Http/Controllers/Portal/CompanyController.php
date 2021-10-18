<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;

class CompanyController extends Controller
{
    public function show($company)
    {
        $model = Company::findOrFail($company);

        return $model;
    }

    public function update($company)
    {
        $model = Company::findOrFail($company);
        $model->update(request()->only(['name', 'address', 'url']));

        return $model;
    }
}
