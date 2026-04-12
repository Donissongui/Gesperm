<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    // Clé primaire personnalisée
    protected $primaryKey = 'id_service';

    // Champs autorisés au mass assignment
    protected $fillable = [
        'nom_service',
    ];

    public function personnels()
    {
        return $this->hasMany(Personnel::class, 'id_service');
    }
}
