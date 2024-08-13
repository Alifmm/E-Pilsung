<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $table = 'votes';
    protected $fillable = [
        "idcalon",
        "iduser",
    ];

    protected $primaryKey = "idvote";
    protected $guarded="idvote";
}
