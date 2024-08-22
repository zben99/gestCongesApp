<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conges extends Model
{
    protected $fillable = [
        'userId',
        'type_conge_id',
        'dateDebut',
        'dateFin',
        'commentaire',
        'status',
        'approved_by_manager', // ID du manager qui approuve
        'approved_by_rh', // ID du responsable RH qui approuve
    ];

    public function employe()
    {
        return $this->belongsTo(User::class, 'UserId');
    }

    public function typeConge()
    {
        return $this->belongsTo(TypeConges::class, 'type_conge_id');
    }

    public function managers()
    {
        return $this->belongsTo(User::class, 'approved_by_manager');
    }

    // Relation pour obtenir les employés sous ce manager
    public function rh()
    {
        return $this->belongsToMany(User::class, 'approved_by_rh');
    }
}
