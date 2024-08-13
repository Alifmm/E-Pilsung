<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calon extends Model
{
    use HasFactory;

    protected $table = 'calons';
    protected $fillable = [
        "idketua",
        "wajahketua",
        "idsekretaris",
        "wajahsekretaris",
        "idbendahara",
        "wajahbendahara",
        "visi",
        "misi",
    ];

    protected $primaryKey = "idcalon";
    protected $guarded="idcalon";

    public function ketua()
    {
        return $this->belongsTo(User::class, 'idketua');
    }

    public function sekretaris()
    {
        return $this->belongsTo(User::class, 'idsekretaris');
    }

    public function bendahara()
    {
        return $this->belongsTo(User::class, 'idbendahara');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'idcalon', 'id');
    }
}
