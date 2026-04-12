<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $primaryKey = 'id_categorie';

    protected $fillable = [
        'nom_categorie',
        'n_order',
        'id_grade'
    ];

    /*
    | Relation Grade
    */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'id_grade');
    }
}
