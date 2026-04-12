<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvisPermission extends Model
{
    // Table associée
    protected $table = 'avis_permissions';

    // Clé primaire
    protected $primaryKey = 'id_avis';

    // Champs remplissables via mass-assignment
    protected $fillable = [
        'id_permission',
        'avis',
        'id_personnel',
        'ordre'
    ];

    // Timestamps activés (created_at / updated_at)
    public $timestamps = true;

    /**
     * Relation avec la permission concernée
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'id_permission');
    }

    /**
     * Relation avec le personnel qui a donné l'avis (optionnel)
     */
    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'id_personnel');
    }

    /**
     * Scope pour récupérer les avis d'une permission par ordre
     */
    public function scopeParOrdre($query, $id_permission)
    {
        return $query->where('id_permission', $id_permission)
            ->orderBy('ordre', 'asc');
    }
}
