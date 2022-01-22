<?php

namespace App\Traits\Client;

use App\Events\Message\MessageAttachment;
use App\Models\Client\Client;
use App\Models\Client\Message;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait MessageTrait
{
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
                if ($extension == 'ico') {
                    Storage::putFileAs("public/uploads/clients/$client->token/$session", $file, "$name.$extension");
                } else {
                    $image = Image::make($file);
                    $image->save("$path/$name.$extension", 30, 'jpg');
                }
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
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function search()
    {
        try {
            $columns = ['message'];

            $length = request()->length;
            $column = request()->column; // index
            $dir = request()->dir;
            $searchValue = request()->search;

            $query = DB::table('client_session_messages as csm')
                ->leftJoin('client_sessions AS cs', 'csm.session_id', '=', 'cs.id')
                ->leftJoin('clients AS c', 'cs.client_id', '=', 'c.id')
                ->leftJoin('users AS u', 'csm.user_id', '=', 'u.id')
                ->leftJoin('user_bio AS ub', 'ub.user_id', '=', 'u.id')
                ->select(
                    'csm.sender AS sender',
                    'csm.message AS message',
                    'cs.session AS session',
                    'c.name AS client',
                    DB::raw("CONCAT(ub.first_name, ' ', ub.last_name) AS agent")
                )
                ->orderBy($columns[$column], $dir)
                ->where(function ($query) use ($searchValue) {
                    $query->where('message', 'like', '%' . $searchValue . '%');
                })
                ->where('csm.sender', '!=', 'session');

            // using tap() to update the fields queried from paginate()
            $tickets = tap($query->paginate($length), function ($init) {
                return $init->getCollection()->transform(function ($item) {
                    // do your customization here
                    // $item->sla = 'Some value...';
                    return $item;
                });
            });

            return ['data' => $tickets, 'draw' => request()->draw];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
