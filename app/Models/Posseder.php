<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posseder extends Model
{
    protected $table = 'posseders';

    protected $primaryKey = 'id_posseder';

    public $timestamps = true;

    protected $fillable = [
        'id_personnel',
        'id_permission',
        'date_début',
        'date_fin',
        'id_motif',
        'id_ville',
        'statut',
        'arrive'
    ];


    /* ===============================
        RELATION PERSONNEL
    =============================== */

    public function personnel()
    {
        return $this->belongsTo(
            Personnel::class,
            'id_personnel',
            'id_personnel'
        );
    }


    /* ===============================
        RELATION PERMISSION
    =============================== */

    public function permission()
    {
        return $this->belongsTo(
            Permission::class,
            'id_permission',
            'id_permission'
        );
    }

    // Relation vers le motif lié
    public function motif()
    {
        return $this->belongsTo(Motif::class, 'id_motif');
    }

    // **Relation vers la ville**
    public function ville()
    {
        return $this->belongsTo(Ville::class, 'id_ville');
    }
}
