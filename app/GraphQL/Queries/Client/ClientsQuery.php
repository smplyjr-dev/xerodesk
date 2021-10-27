<?php

namespace App\GraphQL\Queries\Client;

use App\Models\Client\Client;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class ClientsQuery extends Query
{
    public function type(): Type
    {
        return Type::listOf(GraphQL::type('client'));
    }

    public function args(): array
    {
        return [
            'id'    => ['name' => 'id', 'type' => Type::int()],
            'token' => ['name' => 'token', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['id'])) return Client::where('id', $args['id'])->get();
        if (isset($args['token'])) return Client::where('token', $args['token'])->get();
        if (isset($args['email'])) return Client::where('email', $args['token'])->get();

        return Client::all();
    }
}
