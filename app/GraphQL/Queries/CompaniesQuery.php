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
    public function type(): Type
    {
        return Type::listOf(GraphQL::type('company'));
    }

    public function args(): array
    {
        return [];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return Company::all();
    }
}
