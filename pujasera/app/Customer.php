<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    public $timestamps = false;

    protected $appends = ['saldo_rupiah'];

    protected $fillable = [
        'nama','alamat','nohp','saldo','email','foto'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','id_user');
    }

    public function transaksi()
    {
        return $this->hasMany('App\Transaksi','id_customer');
    }

    public function deposit()
    {
        return $this->hasMany('App\Deposit','id_customer');
    }

    public function refund()
    {
        return $this->hasMany('App\Refund','id_customer');
    }

    public function getPathFotoAttribute()
    {
        return asset('storage/foto/customer/'.$this->foto);
    }

    public function getSaldoRupiahAttribute()
    {
        return toRp($this->saldo, true);
    }

    public static function getDdl($zeroIndex = false)
    {
        $data = self::orderBy('id','desc')->get();
        $ddl = elo2ddl($data, 'nama');
        if($zeroIndex) return [0 => "Semua Customer"] + $ddl;
        return $ddl;
    }
}
