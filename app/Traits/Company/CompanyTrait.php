<?php

namespace App\Traits\Company;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Storage;

trait CompanyTrait
{
    public function picture()
    {
        request()->validate([
            'image' => 'required|image64:jpeg,jpg,png',
            'size'  => 'numeric|max:3000000', // 3mb in bytes
        ], [
            'size.max' => 'The file size should not be greater than 3MB.',
        ]);

        $id      = request()->id;
        $company = Company::findOrFail($id);
        $image   = request()->image;
        $name    = request()->name;
        $old     = request()->old;

        $file = storage_path("app\\public\\uploads\\companies\\$id\\$old");

        // remove the previous profile picture
        if (file_exists($file)) unlink($file);

        $exploded  = explode(',', $image);
        $extension = explode(';', explode('/', $exploded[0])[1])[0];
        $filename  = $name . '-' . uniqid() . '.' . $extension;
        $decoded   = base64_decode($exploded[1]);
        $path      = "public\\uploads\\companies\\$id\\$filename";

        // save to storage directory
        Storage::put($path, $decoded);

        // save to user model
        $company->update([
            'company_picture' => $filename
        ]);

        return response()->json($company->company_picture, 200);
    }
}
