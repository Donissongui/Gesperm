<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $primaryKey = 'id_permission';

    protected $fillable = [
        'type_permission',
        'tranche'
    ];

    // Relation directe vers Posseder
    public function posseders()
    {
        return $this->hasMany(Posseder::class, 'id_permission');
    }

    // Relation vers les avis historisés
    public function avisPermissions()
    {
        return $this->hasMany(AvisPermission::class, 'id_permission');
    }
}
