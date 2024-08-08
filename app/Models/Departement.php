<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = ['name_departement', 'description'];

    public function employes()
    {
        return $this->hasMany(Employe::class, 'departementId');
    }
}
