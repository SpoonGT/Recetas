<?php

namespace App\Models\Estadistica;

use App\Models\Estadistica\Anio;
use App\Models\Estadistica\Estado;
use App\Models\Estadistica\Tramite;
use App\Models\Estadistica\Interesado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estadistica extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estadistica';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['anio_id', 'correlativo', 'interesado_id', 'tramite_id', 'ingreso', 'estado_id', 'resuelto'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'resuelto' => 'boolean',
    ];

    public function anio()
    {
        return $this->hasOne(Anio::class, 'id', 'anio_id');
    }

    public function interesado()
    {
        return $this->hasOne(Interesado::class, 'id', 'interesado_id');
    }

    public function tramite()
    {
        return $this->hasOne(Tramite::class, 'id', 'tramite_id');
    }

    public function estado()
    {
        return $this->hasOne(Estado::class, 'id', 'estado_id');
    }
}
