<?php

namespace App\Traits\SLA;

use Exception;
use Illuminate\Support\Facades\DB;

trait SLATrait
{
    public function datatable()
    {
        try {
            $columns = ['name', 'color', 'range'];

            $length = request()->length;
            $column = request()->column;
            $dir = request()->dir;
            $searchValue = request()->search;

            $query = DB::table('slas')
                ->orderBy($columns[$column], $dir);

            if ($searchValue) {
                $query->where(function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('color', 'like', '%' . $searchValue . '%')
                        ->orWhere('range', 'like', '%' . $searchValue . '%');
                });
            }

            $tickets = $query->paginate($length);

            return ['data' => $tickets, 'draw' => request()->draw];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
