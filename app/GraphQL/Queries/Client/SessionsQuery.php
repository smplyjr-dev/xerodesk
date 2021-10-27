<?php

namespace App\GraphQL\Queries\Client;

use App\Models\Client\Session;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class SessionsQuery extends Query
{
    protected $attributes = [
        'name' => 'Sessions',
        'description' => 'List of all sessions'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('session'));
    }

    public function args(): array
    {
        return [
            'id'      => ['name' => 'id', 'type' => Type::int()],
            'session' => ['name' => 'session', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['id'])) return Session::where('id', $args['id'])->get();
        if (isset($args['session'])) return Session::where('session', $args['session'])->get();

        return Session::all();
    }
}
