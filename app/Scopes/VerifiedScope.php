<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;


/**
 * Filtering scope for list of Votes by status field
 * 
 */
class VerifiedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderByRaw("CASE WHEN status = 'unverified' THEN 0 ELSE 1 END")
                ->orderBy('updated_at', 'desc'); 
    }
}
