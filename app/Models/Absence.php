<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'UserId',
        'motif',
        'dateDebut',
        'dateFin',
        'commentaire',
        'status',
        'approved_by',
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }

    // Relation pour le manager qui approuve l'absence
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
