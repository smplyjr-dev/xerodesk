<?php

namespace App\GraphQL\Mutations\Company;

use App\Models\Company\Company;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Mutation;

class CompanyDeleteMutation extends Mutation
{
    protected $attributes = [
        'name' => 'delete'
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('company'));
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $company = Company::findOrFail($args['id']);
        $company->delete();

        return $company;
    }
}
