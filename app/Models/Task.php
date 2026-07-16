<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property ?string description
 * @property Carbon $due_date
 * @property Carbon $created_at
 * @property TaskStatus $status
 * @property TaskPriority $priority
 * @property int $category_id
 * @property Category $category
 */
class Task extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'due_date',
        'created_at',
        'status',
        'priority',
        'category_id'
    ];

    protected $casts = [
        'due_date' => 'date:d.m.Y',
    ];

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
