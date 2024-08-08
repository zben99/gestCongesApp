<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'employeId', 'motif', 'dateDebut', 'dateFin', 'commentaire', 'status', 'approved_by'
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class, 'employeId');
    }
}
