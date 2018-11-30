<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stan extends Model
{
    protected $table = 'stan';

    public $timestamps = false;

    protected $appends = ['saldo_rupiah'];

    protected $fillable = [
        'nama', 'saldo', 'foto',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','id_user');
    }

    public function hidangan()
    {
        return $this->hasMany('App\Hidangan','id_stan');
    }

    public static function getDdl($zeroIndex = false)
    {
        $data = self::orderBy('id','desc')->get();
        $ddl = elo2ddl($data, 'nama');
        if($zeroIndex) return [0 => "Semua Stan"] + $ddl;
        return $ddl;
    }

    public function getPathFotoAttribute()
    {
        return asset('storage/foto/stan/'.$this->foto);
    }

    public function getSaldoRupiahAttribute()
    {
        return toRp($this->saldo, true);
    }
}
