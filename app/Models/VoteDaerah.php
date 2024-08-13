<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteDaerah extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'votedaerah';
    protected $fillable = [
        "idcalon",
        "iduser",
        "idcabang",
    ];

    protected $primaryKey = "idvote";
    protected $guarded="idvote";

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    public function calonDaerah()
    {
        return $this->belongsTo(CalonDaerah::class, 'idcaldar', 'id');
    }
}
