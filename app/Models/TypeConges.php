<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeConges extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'duree_max',
        'justificatif_requis',
    ];

    public function typeConge()
    {
        return $this->belongsTo(TypeConges::class, 'type_conge_id');
    }
}
