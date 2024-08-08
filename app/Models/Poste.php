<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;

    protected $fillable = ['name_poste', 'description'];

    public function employes()
    {
        return $this->hasMany(Employe::class, 'posteId');
    }
}
