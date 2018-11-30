<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    public $timestamps = false;

    protected $appends = ['status_info', 'total_rupiah', 'selisih_tanggal'];

    protected $dates = ['tanggal'];

    protected $fillable = [
        'jumlah','status','total','id_transaksi', 'id_hidangan'
    ];

    public function transaksi()
    {
        return $this->belongsTo('App\Transaksi','id_transaksi');
    }

    public function hidangan()
    {
        return $this->belongsTo('App\Hidangan','id_hidangan');
    }

    public function getStatusInfoAttribute()
    {
        return $this->getDdlStatus()[$this->status];
    }

    public static function getDdlStatus($zeroIndex = false)
    {
        $ddl = [1 => 'Cart', 2 => 'Dipesan', 3 => 'Diproses', 4 => 'Selesai', 5 => 'Dibatalkan'];
        if($zeroIndex) return [0 => "Semua Status"] + $ddl;
        return $ddl;
    }

    public function getTotalRupiahAttribute()
    {
        return toRp($this->total, true);
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status){
            case 2: $color = 'info'; break;
            case 3: $color = 'primary'; break;
            case 4: $color = 'success'; break;
            case 5: $color = 'danger'; break;
            default: $color ='default';
        }
        return $color;
    }

    public function getSelisihTanggalAttribute()
    {
        return $this->tanggal->diffForHumans();
    }

    public function getNomorMejaAttribute()
    {
        return $this->transaksi->meja->nomor;
    }

    public function getNamaCustomerAttribute()
    {
        return $this->transaksi->customer->nama;
    }

    public function getHidanganDetailAttribute()
    {
        return $this->hidangan->append('nama_kategori')->makeHidden(['id','id_stan','kategori','id_kategori']);
    }
}
