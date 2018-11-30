<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refund';

    public $timestamps = false;

    protected $appends = ['nominal_rupiah', 'selisih_tanggal'];

    protected $dates = ['tanggal'];

    protected $fillable = [
        'id_stan','id_customer','nohp','nominal','alasan'
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
