<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Widget\Widget;
use Illuminate\Support\Facades\Storage;

class WidgetController extends Controller
{
    public function index()
    {
        $model = Widget::all();

        return $model;
    }

    public function show($nanoid)
    {
        $model = Widget::whereNanoid($nanoid)->firstOrFail();

        return $model;
    }

    public function update($nanoid)
    {
        $model = Widget::whereNanoid($nanoid)->firstOrFail();
        $model->update(request()->only([
            'title',
            'sub_title',
            'theme_bg',
            'theme_color',
            'text_bg',
            'text_color',
            'button_bg'
        ]));

        return $model;
    }

    public function picture()
    {
        if (request()->method == 'update') {
            request()->validate([
                'image' => 'required|image64:jpeg,jpg,png',
                'size'  => 'numeric|max:3000000', // 3mb in bytes
            ], [
                'size.max' => 'The file size should not be greater than 3MB.',
            ]);

            $id     = request()->id;
            $widget = Widget::findOrFail($id);
            $image  = request()->image;
            $name   = request()->name;
            $old    = request()->old;

            $file = storage_path("app\\public\\uploads\\widgets\\$id\\$old");

            // remove the previous profile picture
            if (file_exists($file)) unlink($file);

            $exploded  = explode(',', $image);
            $extension = explode(';', explode('/', $exploded[0])[1])[0];
            $filename  = $name . '-' . uniqid() . '.' . $extension;
            $decoded   = base64_decode($exploded[1]);
            $path      = "public\\uploads\\widgets\\$id\\$filename";

            // save to storage directory
            Storage::put($path, $decoded);

            // save to widget model
            $widget->update([
                'picture' => $filename
            ]);

            return response()->json($widget->picture, 200);
        } else {
            $id     = request()->id;
            $widget = Widget::findOrFail($id);
            $old    = request()->old;
            $file   = storage_path("app\\public\\uploads\\widgets\\$id\\$old");

            // remove the previous profile picture
            if (file_exists($file)) unlink($file);

            // save to widget model
            $widget->update([
                'picture' => 'photograph.png'
            ]);

            return response()->json($widget->picture, 200);
        }
    }
}
