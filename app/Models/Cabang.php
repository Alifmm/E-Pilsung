<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    protected $table = 'cabangs';
    protected $primaryKey = "idcabang";
    protected $guarded="idcabang";

    public function users()
    {
        return $this->hasMany(User::class, 'idcabang');
    }
}
