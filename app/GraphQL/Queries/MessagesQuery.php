<?php

namespace App\GraphQL\Queries;

use App\Models\Client\Message;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class MessagesQuery extends Query
{
    protected $attributes = [
        'name' => 'messages',
        'description' => 'List of all messages'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('message'));
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::int()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['id'])) return Message::where('id', $args['id'])->get();

        return Message::all();
    }
}
