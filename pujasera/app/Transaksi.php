<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    public $timestamps = false;

    protected $appends = ['status_info', 'nomor_meja', 'total_rupiah', 'selisih_tanggal'];

    protected $dates = ['tanggal'];

    protected $fillable = [
        'id_meja','id_customer','status','total'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer','id_customer');
    }

    public function pemesanan()
    {
        return $this->hasMany('App\Pemesanan','id_transaksi');
    }

    public function meja()
    {
        return $this->belongsTo('App\Meja','id_meja');
    }

    public function getNomorMejaAttribute()
    {
        //many = isEmpty() // one = ! || empty()
            return empty($this->meja) ? '-' : $this->meja->nomor;
    }

    public function getStatusInfoAttribute()
    {
        return $this->getDdlStatus()[$this->status];
    }

    public static function getDdlStatus($zeroIndex = false)
    {
        $ddl = [1 => 'Cart', 2 => 'Dipesan', 3 => 'Diproses', 4 => 'Selesai', 5 => 'Dibatalkan'];;
        if($zeroIndex) return [0 => "Semua Status"] + $ddl;
        return $ddl;
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

    public function getTotalRupiahAttribute()
    {
        return toRp($this->total, true);
    }

    public function getSelisihTanggalAttribute()
    {
        return $this->tanggal->diffForHumans();
    }

    public function getPesananAmountAttribute()
    {
        $pemesanan = $this->pemesanan();
        return [
            'jumlah_pesanan' => $pemesanan->count(),
            'pesanan_belum_konfirmasi' => $pemesanan->where('status',2)->count(),
            'pesanan_proses' => $pemesanan->where('status',3)->count(),
            'pesanan_selesai' => $pemesanan->where('status',4)->count(),
            'pesanan_batal' => $pemesanan->where('status',5)->count()
        ];
    }
}
