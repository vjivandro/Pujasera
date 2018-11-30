<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table = 'meja';

    public $timestamps = false;

    protected $appends = ['status_info'];

    protected $fillable = [
        'status','nomor'
    ];

    public function getStatusInfoAttribute()
    {
        return $this->getDdlStatus()[$this->status];
    }

    public static function getDdlStatus($zeroIndex = false)
    {
        $ddl = [1 => 'Kosong', 2 => 'Terpakai', 3 => 'Dipesan'];
        if($zeroIndex) return [0 => "Semua Status"] + $ddl;
        return $ddl;
    }

    public static function getDdl($zeroIndex = false)
    {
        $data = self::orderBy('nomor','desc')->get();
        $ddl = elo2ddl($data, 'nomor');
        if($zeroIndex) return [0 => "Semua Meja"] + $ddl;
        return $ddl;
    }
}
