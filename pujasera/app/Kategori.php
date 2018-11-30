<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    public $timestamps = false;

    protected $fillable = [
        'nama'
    ];

    public function hidangan()
    {
        return $this->hasMany('App\Hidangan','id_kategori');
    }

    public static function getDdl($zeroIndex = false)
    {
        $data = self::orderBy('id','desc')->get();
        $ddl = elo2ddl($data, 'nama');
        if($zeroIndex) return [0 => "Semua Kategori"] + $ddl;
        return $ddl;
    }
}
