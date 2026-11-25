<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Representa la tabla `city` de la base de datos World.
 */
class City extends Model
{
    protected $table = 'city';

    protected $primaryKey = 'ID';

    public $timestamps = false;

    /**
     * Una ciudad pertenece a un país.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'CountryCode', 'Code');
    }
}
