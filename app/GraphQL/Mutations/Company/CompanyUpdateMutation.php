<?php

namespace App\GraphQL\Mutations\Company;

use App\Models\Company\Company;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Mutation;

class CompanyUpdateMutation extends Mutation
{
    protected $attributes = [
        'name' => 'update'
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('company'));
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'name' => ['type' => Type::nonNull(Type::string())],
            'address' => ['type' => Type::nonNull(Type::string())],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $company = Company::find($args['id']);
        if (!$company) return null;

        $company->name = $args['name'];
        $company->address = $args['address'];
        $company->save();

        return $company;
    }
}