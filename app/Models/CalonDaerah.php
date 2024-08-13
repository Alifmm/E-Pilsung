<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonDaerah extends Model
{
    use HasFactory;
    protected $table = 'calondaerah';
    protected $fillable = [
        "idketua",
        "wajahketua",
        "idsekretaris",
        "wajahsekretaris",
        "idbendahara",
        "wajahbendahara",
        "visi",
        "misi",
        "cabang",
    ];

    protected $primaryKey = "idcaldar";
    protected $guarded="idcaldar";

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
        return $this->hasMany(VoteDaerah::class, 'idcaldar', 'id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'idcabang');
    }
}
