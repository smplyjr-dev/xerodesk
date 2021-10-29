<?php

namespace App\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'user',
    ];

    public function fields(): array
    {
        return [
            'id'                => ['type' => Type::nonNull(Type::int())],
            'company_id'        => ['type' => Type::nonNull(Type::int())],
            'username'          => ['type' => Type::nonNull(Type::string())],
            'email'             => ['type' => Type::nonNull(Type::string())],
            'profile_picture'   => ['type' => Type::nonNull(Type::string())],
            'email_verified_at' => ['type' => Type::nonNull(Type::string())],
            'password'          => ['type' => Type::nonNull(Type::string())],
            'status'            => ['type' => Type::nonNull(Type::int())],
            'remember_token'    => ['type' => Type::string()],
            'created_at'        => ['type' => Type::nonNull(Type::string())],
            'updated_at'        => ['type' => Type::nonNull(Type::string())],
            'deleted_at'        => ['type' => Type::string()],

            // start of relationship query
            'bio' => [
                'type' => GraphQL::type('bio'),
                'description' => 'The bio of the each users',
            ],
            'company' => [
                'type' => GraphQL::type('company'),
                'description' => 'The company where the user belongs to',
            ]
        ];
    }
}
