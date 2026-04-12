<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $primaryKey = 'id_grade';

    protected $fillable = [
        'libelle_grade'
    ];

    /*
    | Relation Personnel
    */
    public function personnels()
    {
        return $this->hasMany(Personnel::class, 'id_grade');
    }

    /*
    | Relation Category (si utilisée)
    */
    public function categories()
    {
        return $this->hasMany(Categorie::class, 'id_grade');
    }
}
