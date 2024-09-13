<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rappel extends Model
{
    use HasFactory;

    protected $fillable = [
        'conge_id',
        'dateDebutRappel',
        'dateFinRappel',
    ];

    public function conge()
    {
        return $this->belongsTo(Conges::class, 'conge_id');
    }
}
