<?php

namespace App\Models;

use App\Models\TypeAbsences;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AbsenceControlleur;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'UserId', // Correction du nom du champ pour correspondre à la convention Laravel
        'type_absence_id',
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


    public function typeAbsence()
    {
        return $this->belongsTo(TypeAbsences::class, 'type_absence_id');
    }


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

        // Dans le modèle Absence

}
