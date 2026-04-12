<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authentification extends Model
{
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'user_name',
        'mot_de_passe',
        'avis'
    ];

    protected $hidden = [
        'mot_de_passe'
    ];
}
