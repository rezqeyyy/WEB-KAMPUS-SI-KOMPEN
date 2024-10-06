<?php

namespace App\Models;

use App\Traits\UuidTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MMahasiswa extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbl_mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    public $incrementing = false; // Jika id_mahasiswa bukan auto-increment
    protected $keyType = 'string'; // Jika id_mahasiswa adalah UUID

    // Definisikan atribut yang dapat diisi
    protected $fillable = [
        'id_mahasiswa', // Tambahkan jika menggunakan UUID
        'kode_user',
        'nama_user',
        'kelas',
        'prodi',
        'semester',
        'jumlah_terlambat',
        'jumlah_alfa',
        'total',
        'password', // Perlu untuk autentikasi
    ];

    // Menyembunyikan atribut saat serialisasi
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function pengajuans()
    {
        return $this->hasMany(MPengajuan::class, 'kode_user', 'kode_user');
    }
}
