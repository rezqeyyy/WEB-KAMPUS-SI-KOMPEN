<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MPengajuan extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'tbl_pengajuan';

    // Menentukan primary key
    protected $primaryKey = 'id_pengajuan';

    // Mengatur primary key sebagai non-incrementing dan tipe string
    public $incrementing = false;
    protected $keyType = 'string';

    // Atribut yang dapat diisi
    protected $fillable = [
        'kode_user',
        'kode_kegiatan',
        'nama_user',
        'waktu',
        'sisa',
        'keterangan',
        'tanggal_pengajuan',
        'before',
        'after',
        'status_approval1',
        'keterangan_approval1',
        'approval1_by',
        'status_approval2',
        'keterangan_approval2',
        'approval2_by',
        'status_approval3',
        'keterangan_approval3',
        'approval3_by',
        'status',
        'user_create',
        'user_update',
        'uid',
        'kode_pekerjaan',
        'jam_pekerjaan', // Kolom baru jam_pekerjaan
        'batas_pekerjaan', // Kolom baru batas_pekerjaan
        'penanggung_jawab',
        'id_penanggung_jawab',
        'perkiraan_sisa_jam'

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pengajuan) {

            // $maxKodeKegiatan = MPengajuan::max('kode_kegiatan');
            // $pengajuan->kode_kegiatan = $maxKodeKegiatan + 1;

            $maxKodeKegiatan = DB::table('tbl_pengajuan')
                ->select(DB::raw('MAX(CAST(kode_kegiatan as DECIMAL))as max_kode'))
                ->first()
                ->max_kode;

            $pengajuan->kode_kegiatan = $maxKodeKegiatan ? $maxKodeKegiatan + 1 : 1;
        });
    }
    // Relasi ke model MPekerjaan
    public function mahasiswa()
    {
        return $this->belongsTo(MMahasiswa::class, 'kode_user', 'kode_user');
    }
    public function pekerjaan()
    {
        return $this->belongsTo(MPekerjaan::class, 'kode_pekerjaan', 'kode_pekerjaan');
    }

    public function details()
    {
        return $this->hasMany(MPengajuanDetail::class, 'id_pengajuan', 'id_pengajuan');
    }
}
