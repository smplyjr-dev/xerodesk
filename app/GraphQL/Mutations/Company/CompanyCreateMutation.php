<?php

namespace App\GraphQL\Mutations\Company;

use App\Models\Company\Company;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Mutation;

class CompanyCreateMutation extends Mutation
{
    protected $attributes = [
        'name' => 'create'
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('company'));
    }

    public function args(): array
    {
        return [
            'name' => ['type' => Type::nonNull(Type::string())],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $company = new Company();
        $company->name = $args['name'];
        $company->save();

        return $company;
    }
}
