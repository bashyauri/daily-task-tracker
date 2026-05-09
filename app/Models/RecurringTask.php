<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int|null $category_id
 * @property string $title
 * @property string|null $description
 * @property string $frequency
 * @property array<array-key, mixed>|null $frequency_config
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereFrequencyConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTask withoutTrashed()
 * @mixin \Eloquent
 */
class RecurringTask extends Model
{
    use HasUuids;
    use SoftDeletes;
    protected $fillable = [
    'user_id',
    'category_id',
    'title',
    'description',
    'frequency',
    'frequency_config',
    'start_date',
    'end_date'
    ];
    #[Override]
    protected function casts()
    {
        return [
            'frequency_config' => 'array',
            'start_date' => 'date',
            'end_date' => 'date'
        ];
    }
     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

}
