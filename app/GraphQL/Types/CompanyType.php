<?php

namespace App\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CompanyType extends GraphQLType
{
    protected $attributes = [
        'name' => 'companies',
    ];

    public function fields(): array
    {
        return [
            'id'      => ['type' => Type::nonNull(Type::int())],
            'name'    => ['type' => Type::nonNull(Type::string())],
            'address' => ['type' => Type::nonNull(Type::string())],
            'url'     => ['type' => Type::nonNull(Type::string())],
            'abbr'    => ['type' => Type::nonNull(Type::string())],

            // start of relationship query
            'users' => [
                'type' => Type::listOf(GraphQL::type('user')),
                'description' => 'List of users belongs to this company',
            ]
        ];
    }
}
