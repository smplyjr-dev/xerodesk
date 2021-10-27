<?php

namespace App\GraphQL\Types\User;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class BioType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Bio',
        'description' => 'List of user\'s bio',
    ];

    public function fields(): array
    {
        return [
            'id'          => ['type' => Type::nonNull(Type::int())],
            'user_id'     => ['type' => Type::nonNull(Type::int())],
            'first_name'  => ['type' => Type::nonNull(Type::string())],
            'middle_name' => ['type' => Type::nonNull(Type::string())],
            'last_name'   => ['type' => Type::nonNull(Type::string())],
            'dob'         => ['type' => Type::nonNull(Type::string())],
            'facebook'    => ['type' => Type::nonNull(Type::string())],
            'twitter'     => ['type' => Type::nonNull(Type::string())],
            'linkedin'    => ['type' => Type::nonNull(Type::string())],
            'created_at'  => ['type' => Type::nonNull(Type::string())],
            'updated_at'  => ['type' => Type::nonNull(Type::string())],

            // start of relationship
            'user' => [
                'type' => Type::listOf(GraphQL::type('user')),
                'description' => 'The user where the bio belongsTo',
            ]
        ];
    }
}
