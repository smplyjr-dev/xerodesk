<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Taggable\Taggable;
use App\Traits\Client\TagTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class TaggableController extends Controller
{
    use TagTrait;

    public function index()
    {
        $tags = Taggable::all();

        return response()->json($tags, 200);
    }

    public function store()
    {
        DB::beginTransaction();

        try {
            // create the taggable, if not exist
            $taggable = Taggable::firstOrCreate([
                'name' => request()->name,
            ], [
                'name' => request()->name,
            ]);

            DB::commit();

            $response = [
                "status"  => "success",
                "message" => "A new taggable has been created.",
                "data"    => $taggable
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            DB::rollBack();

            $response = [
                "status"        => "error",
                "message"       => "Something went wrong. Please contact your system administrator.",
                "error_code"    => $e->getCode(),
                "error_message" => $e->getMessage()
            ];

            return response()->json($response, 503);
        }
    }

    public function show($id)
    {
    }

    public function update($id)
    {
    }

    public function destroy($id)
    {
    }
}
