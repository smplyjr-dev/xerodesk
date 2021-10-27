<?php

namespace App\GraphQL\Queries\User;

use App\Models\User\User;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'Users',
        'description' => 'List of all users'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('user'));
    }

    public function args(): array
    {
        return [
            'id'       => ['name' => 'id', 'type' => Type::int()],
            'username' => ['name' => 'username', 'type' => Type::string()],
            'email'    => ['name' => 'email', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['id'])) return User::where('id', $args['id'])->get();
        if (isset($args['username'])) return User::where('username', $args['username'])->get();
        if (isset($args['email'])) return User::where('email', $args['email'])->get();

        return User::all();
    }
}
