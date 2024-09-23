<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'matricule', 'email', 'telephone1', 'telephone2', 'birth_date',
        'password', 'profil', 'departementId', 'posteId', 'arrival_date', 'initialization_date', 'initial', 'pris', 'reste','jouissance_pdf'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departementId');
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class, 'posteId');
    }

    // Relation pour obtenir les managers de cet utilisateur
    public function managers()
    {
        return $this->belongsToMany(User::class, 'user_manager', 'user_id', 'manager_id');
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'user_manager', 'manager_id', 'user_id');
    }

    public function rhEmployees()
    {
        return $this->belongsToMany(User::class, 'user_manager', 'rh_id', 'user_id');
    }

    // Relation pour obtenir le responsable RH assigné à cet utilisateur
    public function rh()
    {
        return $this->belongsToMany(User::class, 'user_manager', 'user_id', 'rh_id');
    }




    // Relation pour obtenir les employés dont ce responsable RH est assigné
    public function employeesUnderRh()
    {
        return $this->hasMany(User::class, 'rh_id');
    }



    // Relation avec le modèle `Conges`
    public function conges()
    {
        return $this->hasMany(Conges::class, 'userId');
    }



    // Relation avec le modèle `Absence`
    public function absences()
    {
        return $this->hasMany(Absence::class, 'userId');
    }

        // Relation pour les managers associés à un responsable RH
    public function rhManagers()
    {
        return $this->hasMany(User::class, 'rh_manager_id'); // rh_manager_id est la colonne qui référence le responsable RH pour les managers
    }
    
}
