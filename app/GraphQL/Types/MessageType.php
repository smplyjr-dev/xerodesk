<?php

namespace App\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MessageType extends GraphQLType
{
    protected $attributes = [
        'name' => 'message',
    ];

    public function fields(): array
    {
        return [
            'id'         => ['type' => Type::nonNull(Type::int())],
            'session_id' => ['type' => Type::nonNull(Type::int())],
            'user_id'    => ['type' => Type::nonNull(Type::int())],
            'hash'       => ['type' => Type::nonNull(Type::string())],
            'sender'     => ['type' => Type::nonNull(Type::string())],
            'message'    => ['type' => Type::nonNull(Type::string())],
            'reply_to'   => ['type' => Type::nonNull(Type::string())],
            'is_read'    => ['type' => Type::nonNull(Type::boolean())],
            'created_at' => ['type' => Type::nonNull(Type::string())],
            'updated_at' => ['type' => Type::nonNull(Type::string())],

            // start of relationship
            'session' => [
                'type' => GraphQL::type('session'),
                'description' => 'The session where the message belongs to',
            ]
        ];
    }
}
