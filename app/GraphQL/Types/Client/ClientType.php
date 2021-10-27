<?php

namespace App\GraphQL\Types\Client;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ClientType extends GraphQLType
{
    protected $attributes = [
        'name' => 'clients',
    ];

    public function fields(): array
    {
        return [
            'id'         => ['type' => Type::nonNull(Type::int())],
            'company_id' => ['type' => Type::nonNull(Type::int())],
            'token'      => ['type' => Type::nonNull(Type::string())],
            'email'      => ['type' => Type::nonNull(Type::string())],
            'name'       => ['type' => Type::nonNull(Type::string())],
            'phone'      => ['type' => Type::nonNull(Type::string())],
            'picture'    => ['type' => Type::nonNull(Type::string())],

            // start of relationship query
            'sessions' => [
                'type' => Type::listOf(GraphQL::type('session')),
                'description' => 'List of sessions belongs to this user',
            ]
        ];
    }
}
