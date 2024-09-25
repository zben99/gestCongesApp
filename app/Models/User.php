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





        /**
     * Relation pour récupérer le manager associé à l'utilisateur.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Relation pour récupérer le RH associé à l'utilisateur.
     */
    public function rh()
    {
        return $this->belongsTo(User::class, 'rh_id');
    }


       /**
     * Relation pour récupérer les employés associés à un manager.
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Relation pour récupérer les employés associés à un RH.
     */
    public function rhEmployees()
    {
        return $this->hasMany(User::class, 'rh_id');
    }

}
