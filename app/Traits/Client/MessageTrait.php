<?php

namespace App\Traits\Client;

use App\Models\Client\Client;
use App\Models\Client\Message;
use App\Models\Client\Session;
use Illuminate\Support\Facades\File;

trait MessageTrait
{
    public function session($session)
    {
        $session = Session::with(['messages', 'messages.attachments'])
            ->whereSession($session)
            ->first();

        return response()->json($session, 200);
    }

    public function attachment($hash)
    {
        try {
            request()->validate([
                'file' => 'required|mimes:csv,doc,docx,gif,htm,html,ico,jpeg,jpg,png,pdf,pptx,rar,txt,xls,xlsx,zip,7z|max:20000'
            ]);

            $client    = Client::findOrFail(request()->id);
            $extension = request()->extension;
            $session   = request()->session;
            $name      = request()->name . '-' . time();
            $file      = request()->file;

            // save to database
            $message = Message::whereHash($hash)->firstOrFail();
            $attachment = $message->attachments()->create([
                'name'      => $name,
                'extension' => $extension
            ]);

            // if attachment is image, optimize it
            if (in_array($extension, ['ico', 'jpeg', 'jpg', 'png'])) {
                $path = storage_path("app/public/uploads/clients/$client->token/$session");

                // create the path if not exist
                if (!File::isDirectory($path)) File::makeDirectory($path, 0777, true);
                // if (!file_exists($path)) mkdir($path, 777, true);

                // save to directory
                $image = \Image::make($file);
                $image->save("$path/$name.$extension", 30, 'jpg');
            } else {
                $path = "\\public\\uploads\\clients\\$client->token\\$session";

                // save to directory
                $file->storeAs($path, $name . '.' . $extension);
            }

            return response()->json($attachment, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
