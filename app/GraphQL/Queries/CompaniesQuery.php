<?php

namespace App\GraphQL\Queries;

use App\Models\Company\Company;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class CompaniesQuery extends Query
{
    protected $attributes = [
        'name' => 'Companies',
        'description' => 'List of all companies'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('company'));
    }

    public function args(): array
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['id'])) return Company::where('id', $args['id'])->get();

        return Company::all();
    }
}
