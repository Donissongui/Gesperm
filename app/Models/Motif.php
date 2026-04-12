<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motif extends Model
{
    // Définir la clé primaire si ce n'est pas 'id'
    protected $primaryKey = 'id_motif';

    // Attributs assignables en masse
    protected $fillable = [
        'libelle_motif',
    ];

    // Si tu n'utilises pas les timestamps, sinon Laravel gère created_at et updated_at automatiquement
    public $timestamps = true;

    // Exemple de relation : si un Posseder a un Motif
    public function posseders()
    {
        return $this->hasMany(Posseder::class, 'id_motif');
    }
}
