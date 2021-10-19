<?php

namespace App\Exports;

use App\Models\Client\Session;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SessionsExport implements FromQuery, WithHeadings, WithMapping
{
    public $request;

    public function __construct()
    {
        $this->request = json_decode(request('refine'), true);
    }

    public function query()
    {
        $from = Carbon::createFromDate($this->request['range'][0]);
        $to = Carbon::createFromDate($this->request['range'][1]);

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

        if ($this->request['fields']['client']) $field[] = $session->client->name;
        if ($this->request['fields']['title']) $field[] = $session->title;
        if ($this->request['fields']['session']) $field[] = $session->session;
        if ($this->request['fields']['priority']) $field[] = $priority['name'] ?? null;
        if ($this->request['fields']['group']) $field[] = $group;
        if ($this->request['fields']['agent']) $field[] = $agent;
        if ($this->request['fields']['status']) $field[] = $status['name'] ?? null;
        if ($this->request['fields']['timestamp']) $field[] = $session->created_at;

        return $field;
    }

    public function headings(): array
    {
        $headings = [];

        if ($this->request['fields']['client']) $headings[] = 'Client';
        if ($this->request['fields']['title']) $headings[] = 'Title';
        if ($this->request['fields']['session']) $headings[] = 'Session';
        if ($this->request['fields']['priority']) $headings[] = 'Priority';
        if ($this->request['fields']['group']) $headings[] = 'Group';
        if ($this->request['fields']['agent']) $headings[] = 'Agent';
        if ($this->request['fields']['status']) $headings[] = 'Status';
        if ($this->request['fields']['timestamp']) $headings[] = 'Timestamp';

        return $headings;
    }
}
