<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwner(Scope $scope, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        return $scope->where('user_id', $user->id);
    }

    public function scopeFilter(Builder $builder, $filters = []): Builder
    {

        foreach ($filters as $key => $filter) {

            $builder->where($key, $filter);

        }

        return $builder;
    }

}
