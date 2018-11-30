<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;

    protected $fillable = [
        'name', 'username', 'password', 'api_token', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getStan()
    {
        return [
            'nama' => $this->stan->nama,
            'saldo' => $this->stan->saldo,
            'saldo_rupiah' => toRp($this->stan->saldo, true),
            'foto' => asset('storage/foto/stan/'.$this->stan->foto),
            'username' => $this->username,
        ];
    }

    public function getCustomer()
    {
        return [
            'nama' => $this->customer->nama,
            'saldo' => $this->customer->saldo,
            'saldo_rupiah' => toRp($this->customer->saldo, true),
            'foto' => asset('storage/foto/customer/'.$this->customer->foto),
            'alamat' => $this->customer->alamat,
            'nohp' => $this->customer->nohp,
            'email' => $this->customer->email,
            'username' => $this->username,
        ];
    }

    public function stan()
    {
        return $this->hasOne('App\Stan','id_user');
    }

    public function customer()
    {
        return $this->hasOne('App\Customer','id_user');
    }

}
