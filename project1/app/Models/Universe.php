<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Universe
 *
 * @property int $id_universe
 * @property string|null $universe
 * @property string|null $company
 * @property string|null $age
 * @property Collection|Character[] $characters
 */
class Universe extends Model
{
    protected $table = 'universes';

    /**
     * @var string
     */
    protected $connection = 'mysql';

    protected $primaryKey = 'id_universe';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_universe',
        'universe',
        'company',
        'age',
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
            'id_universe' => 'integer',
            'universe' => 'string',
            'company' => 'string',
            'age' => 'string',
        ];
    }

    /**
     * @return HasMany<Character, $this>
     */
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'id_universe', 'id_universe');
    }
}
