<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conges extends Model
{
    protected $fillable = [
        'employeId',
        'typeConges',
        'dateDebut',
        'dateFin',
        'commentaire',
        'status',
        'approved_by_manager', // ID du manager qui approuve
        'approved_by_rh', // ID du responsable RH qui approuve
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class, 'employeId');
    }
}
