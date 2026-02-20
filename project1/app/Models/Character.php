<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Character
 *
 * @property int $id_character
 * @property string|null $name
 * @property string|null $real_name
 * @property string|null $gender
 * @property int|null $id_universe
 * @property Universe $universe
 */
class Character extends Model
{
    protected $table = 'characters';

    /**
     * @var string
     */
    protected $connection = 'mysql';

    protected $primaryKey = 'id_character';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_character',
        'name',
        'real_name',
        'gender',
        'id_universe',
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
            'id_character' => 'integer',
            'name' => 'string',
            'real_name' => 'string',
            'gender' => 'string',
            'id_universe' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Universe, $this>
     */
    public function universe(): BelongsTo
    {
        return $this->belongsTo(Universe::class, 'id_universe', 'id_universe');
    }
}
