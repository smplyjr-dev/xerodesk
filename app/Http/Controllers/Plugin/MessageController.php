<?php

namespace App\Http\Controllers\Plugin;

use App\Events\Message\MessageAttachment;
use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Client\Message;
use App\Models\Client\Session;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MessageController extends Controller
{
    public function store()
    {
        DB::beginTransaction();

        try {
            $session = Session::where('session', request('session'))->firstOrFail();

            $message = Message::create([
                'session_id'   => $session->id,
                'user_id'      => request()->user_id,
                'hash'         => request()->hash,
                'sender'       => request()->sender,
                'message'      => request()->message,
                'reply_to'     => request()->reply_to
            ]);

            DB::commit();

            $response = [
                "status"  => "success",
                "message" => "Your chat conversation has been saved successfully.",
                "data"    => $message->fresh()
            ];

            return response()->json($response, 200);
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

            // send the event for realtime purposes after the attachment has been saved to the storage
            $s = $message->first()->session()->first();
            $c = $s->client()->first();
            MessageAttachment::dispatch($message, $attachment, $s, $c);

            return response()->json($attachment, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
