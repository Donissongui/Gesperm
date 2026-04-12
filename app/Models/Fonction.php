<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{

    // Clé primaire personnalisée
    protected $primaryKey = 'id_fonction';

    // Champs autorisés au mass assignment
    protected $fillable = [
        'nom_fonction',
    ];

    public function personnels()
    {
        return $this->hasMany(Personnel::class, 'id_fonction');
    }
}
