<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brigade extends Model
{
    protected $primaryKey = 'id_brigade';

    protected $fillable = [
        'nom_brigade'
    ];

    public function personnels()
    {
        return $this->hasMany(Personnel::class, 'id_brigade');
    }
}
