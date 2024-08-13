<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom', 'prenom', 'matricule', 'email', 'telephone1', 'telephone2', 'birthDate',
        'password', 'profil', 'departementId', 'posteId', 'arrivalDate', 'initializationDate', 'initial', 'pris', 'reste'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departementId');
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class, 'posteId');
    }
// Relation pour obtenir les managers de l'utilisateur
    public function managers()
    {
        return $this->belongsToMany(User::class, 'user_manager', 'user_id', 'manager_id');
    }

    // Relation pour obtenir les employés sous ce manager
    public function employees()
    {
        return $this->belongsToMany(User::class, 'user_manager', 'manager_id', 'user_id');
    }


   /**
     * Relation avec le modèle `Conges`.
     */
    public function conges()
    {
        return $this->hasMany(Conges::class, 'userId');
    }

    /**
     * Relation avec le modèle `Absence`.
     */
    public function absences()
    {
        return $this->hasMany(Absence::class, 'userId');
    }

}
