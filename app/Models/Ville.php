<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    // Nom de la table (facultatif si le modèle suit la convention "villes")
    protected $table = 'villes';

    // Clé primaire
    protected $primaryKey = 'id_ville';

    // Champs autorisés pour l'insertion / mise à jour
    protected $fillable = [
        'nom_ville',
    ];

    // Timestamps automatiques
    public $timestamps = true;

    // Exemple : une ville peut être associée à plusieurs permissions (via Posseder)
    public function posseders()
    {
        return $this->hasMany(Posseder::class, 'id_ville');
        // Assure-toi que Posseder a bien 'ville_id'
    }
}
