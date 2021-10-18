<?php

namespace App\Exports;

use App\Models\Client\Session;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SessionsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        $from = Carbon::createFromDate(request('range')[0]);
        $to = Carbon::createFromDate(request('range')[1]);

        return Session::query()
            ->whereBetween('created_at', [$from, $to->addDay()]);
    }

    /**
     * @var Session $session
     */
    public function map($session): array
    {
        $field    = [];
        $group    = !$session->group_id ? null : $session->group->name;
        $agent    = !$session->user_id ? null : $session->user->bio->first_name . ' ' . $session->user->bio->last_name;
        $priority = collect(constants('ticket.priority'))->where('id', $session->priority)->first();
        $status   = collect(constants('ticket.status'))->where('id', $session->status)->first();

        if (request('fields')['client']) $field[] = $session->client->name;
        if (request('fields')['title']) $field[] = $session->title;
        if (request('fields')['session']) $field[] = $session->session;
        if (request('fields')['priority']) $field[] = $priority['name'] ?? null;
        if (request('fields')['group']) $field[] = $group;
        if (request('fields')['agent']) $field[] = $agent;
        if (request('fields')['status']) $field[] = $status['name'] ?? null;
        if (request('fields')['timestamp']) $field[] = $session->created_at;

        return $field;
    }

    public function headings(): array
    {
        $headings = [];

        if (request('fields')['client']) $headings[] = 'Client';
        if (request('fields')['title']) $headings[] = 'Title';
        if (request('fields')['session']) $headings[] = 'Session';
        if (request('fields')['priority']) $headings[] = 'Priority';
        if (request('fields')['group']) $headings[] = 'Group';
        if (request('fields')['agent']) $headings[] = 'Agent';
        if (request('fields')['status']) $headings[] = 'Status';
        if (request('fields')['timestamp']) $headings[] = 'Timestamp';

        return $headings;
    }
}
