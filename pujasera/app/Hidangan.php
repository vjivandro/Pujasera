<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hidangan extends Model
{
    protected $table = 'hidangan';

    public $timestamps = false;

    protected $appends = ['path_foto','harga_rupiah','stock_info'];

    protected $fillable = [
        'nama', 'stock', 'harga', 'id_stan', 'id_kategori', 'foto'
    ];

    public function kategori()
    {
        return $this->belongsTo('App\Kategori','id_kategori');
    }

    public function stan()
    {
        return $this->belongsTo('App\Stan','id_stan');
    }

    public function getPathFotoAttribute()
    {
        return asset('storage/foto/hidangan/'.$this->foto);
    }

    public function getHargaRupiahAttribute()
    {
        return toRp($this->harga, true);
    }

    public function getNamaKategoriAttribute()
    {
        return $this->kategori->nama;
    }

    public function getStockInfoAttribute()
    {
        return $this->getDdlStock()[$this->stock];
    }

    public static function getDdlStock($zeroIndex = false)
    {
        $ddl = [1 => 'Kosong', 2 => 'Ada'];
        if($zeroIndex) return [0 => "Semua Stock"] + $ddl;
        return $ddl;
    }

}
