<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAbsences extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'duree_max',
        'justificatif_requis',
        'deductible_conges',
        'jours_deductibles_apres',
    ];



}
