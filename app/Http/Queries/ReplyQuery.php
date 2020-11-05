<?php

namespace App\Http\Queries;

use App\Models\Reply;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReplyQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Reply::query());
        $this->allowedIncludes('user', 'topic', 'topic.user');
    }
}