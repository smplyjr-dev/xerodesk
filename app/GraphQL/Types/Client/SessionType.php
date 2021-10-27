<?php

namespace App\GraphQL\Types\Client;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SessionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'sessions',
    ];

    public function fields(): array
    {
        return [
            'id'              => ['type' => Type::nonNull(Type::int())],
            'client_id'       => ['type' => Type::nonNull(Type::int())],
            'group_id'        => ['type' => Type::nonNull(Type::int())],
            'user_id'         => ['type' => Type::nonNull(Type::int())],
            'session'         => ['type' => Type::nonNull(Type::string())],
            'title'           => ['type' => Type::nonNull(Type::string())],
            'priority'        => ['type' => Type::nonNull(Type::string())],
            'token'           => ['type' => Type::nonNull(Type::string())],
            'category'        => ['type' => Type::nonNull(Type::int())],
            'resolution_code' => ['type' => Type::nonNull(Type::string())],
            'solution'        => ['type' => Type::nonNull(Type::string())],
            'is_read'         => ['type' => Type::nonNull(Type::int())],
            'status'          => ['type' => Type::nonNull(Type::int())],
        ];
    }
}
