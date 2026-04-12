<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    protected $primaryKey = 'id_personnel';

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'type_personnel',
        'id_grade',
        'id_brigade',
        'id_service',
        'id_fonction'
    ];

    /* Relations */

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'id_grade');
    }

    public function brigade()
    {
        return $this->belongsTo(Brigade::class, 'id_brigade');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function fonction()
    {
        return $this->belongsTo(Fonction::class, 'id_fonction');
    }

    // Relation vers les avis historisés
    public function avisPermissions()
    {
        return $this->hasMany(AvisPermission::class, 'id_personnel');
    }

    // Relation inverse vers User
    public function user()
    {
        return $this->hasOne(User::class, 'personnel_id');
    }

    /* ===============================
        RELATION POSSEDER (PIVOT)
    =============================== */

    // Relation directe vers Posseder
    public function posseders()
    {
        return $this->hasMany(Posseder::class, 'id_personnel');
    }
}
