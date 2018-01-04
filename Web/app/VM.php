<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class VM extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vm';

    public $timestamps = false;

    //Laravel ne sait pas que mon id est id_vm et non id
    protected $primaryKey = 'id_vm';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_utilisateur', 'date_creation', 'nom', 'description', 'statut', 'os', 'cpu', 'ram', 'unite_ram',
        'sto_l', 'unite_sto_l', 'sto_r', 'unite_sto_r',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function unite() {
        return $this->hasMany('App\Unite');
    }
}
