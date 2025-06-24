<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Employee extends Model implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'db_pegawai';
    protected $primaryKey = 'id';

    protected $fillable = [
        'no_reg',
        'nip_pegawai',
        'nip_pns',
        'gelar_depan',
        'gelar_belakang',
        'nama_pegawai',
        'alamat',
        'tmpt_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'id_goldarah',
        'id_status_kawin',
        'id_agama',
        'id_pendidikan',
        'id_jenis_pegawai',
        'id_status_pegawai',
        'id_spesialis',
        'sub_spesialis',
        'id_unit_induk',
        'id_unit_kerja',
        'id_pangkat',
        'id_berkala',
        'id_jabatan',
        'id_unit_jabatan',
        'id_gaji',
        'pjs',
        'hp',
        'email',
        'no_npwp',
        'file_npwp',
        'no_ktp',
        'file_ktp',
        'no_nbm',
        'file_nbm',
        'foto',
        'kode_arsip',
        'idf',
        'kode_dpjp',
        'file_cv',
        'tgl_masuk',
        'tgl_keluar',
        'status_pns',
        'status_aktif',
        'filekk',
        'id_pelamar',
        'id_urutan',
        'id_pk',
        'idv1',
        'hapus',
        'tgl_insert',
        'tgl_update',
        'user_update',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'status_aktif' => 'boolean',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return 'karepanit' . now()->format('d');
    }
}
