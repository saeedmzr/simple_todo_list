<?php

namespace App\Models;

use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

#[ObservedBy([TaskObserver::class])]
class Task extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime:Y-m-d H:i:s',
        'deadline' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwner($query, $userId = null)
    {
        if (!$userId) {
            $userId = request()->user()->id;
        }
        return $this->where('user_id', $userId);
    }

    public function scopeFilters(Builder $builder, array $filters = []): Builder
    {
        foreach ($filters as $key => $filter) {
            $builder->where($key, $filter);
        }
        return $builder;
    }

}
