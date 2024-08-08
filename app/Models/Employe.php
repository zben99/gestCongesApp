<?php
namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class Employe extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'nom', 'prenom', 'matricule', 'email', 'telephone1', 'telephone2', 'dateNaissance',
        'password', 'profil', 'departementId', 'posteId', 'dateArrive', 'initial', 'pris', 'reste'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departementId');
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class, 'posteId');
    }

    public function conges()
    {
        return $this->hasMany(Conges::class, 'employeId');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class, 'employeId');
    }
    
}
