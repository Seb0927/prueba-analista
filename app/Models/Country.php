<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa la tabla `country` de la base de datos World.
 *
 * La clave primaria es el código ISO de tres letras (`Code`) y no es autoincremental.
 */
class Country extends Model
{
    protected $table = 'country';

    protected $primaryKey = 'Code';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    /**
     * Un país tiene muchas ciudades.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'CountryCode', 'Code');
    }
}
