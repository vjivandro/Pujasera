<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposit';

    public $timestamps = false;

    protected $appends = ['nominal_rupiah', 'selisih_tanggal'];

    protected $dates = ['tanggal'];

    protected $fillable = [
        'id_stan','id_customer','nohp','nominal'
    ];

    public function getNominalRupiahAttribute()
    {
        return toRp($this->nominal, true);
    }

    public function getSelisihTanggalAttribute()
    {
        return $this->tanggal->diffForHumans();
    }
}
