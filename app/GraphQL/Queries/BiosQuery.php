<?php

namespace App\GraphQL\Queries;

use App\Models\User\UserBio;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class BiosQuery extends Query
{
    protected $attributes = [
        'name' => 'bios',
        'description' => 'List of all bios'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('bio'));
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::int()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['id'])) return UserBio::where('id', $args['id'])->get();

        return UserBio::all();
    }
}
