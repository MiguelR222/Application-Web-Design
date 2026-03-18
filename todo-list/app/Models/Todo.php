<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Todo
 *
 * @property int $todo_id
 * @property string $name
 * @property string $description
 * @property Carbon $start_date
 * @property Carbon $due_date
 */
class Todo extends Model
{
    protected $table = 'todo';

    /**
     * @var string
     */
    protected $connection = 'mysql';

    protected $primaryKey = 'todo_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'todo_id',
        'name',
        'description',
        'start_date',
        'due_date',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'todo_id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'start_date' => 'datetime',
            'due_date' => 'datetime',
        ];
    }
}
