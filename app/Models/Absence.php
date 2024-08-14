<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AbsenceControlleur;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'UserId', // Correction du nom du champ pour correspondre à la convention Laravel
        'motif',
        'dateDebut',
        'dateFin',
        'commentaire',
        'status',
        'approved_by',
    ];
    protected $casts = [
        'dateDebut' => 'datetime',
        'dateFin' => 'datetime',
    ];

    // Relation avec le modèle User (l'employé associé à l'absence)
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
