<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPengajuanDetail extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_detail';

    protected $primaryKey = 'id_pengajuan_detail';


    protected $fillable = [
        'kode_pekerjaan', 'kode_kegiatan', 'nama_pekerjaan', 'jam_pekerjaan', 'batas_pekerja',
        'before_pekerjaan', 'after_pekerjaan', 'user_create', 'user_update', 'uid'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(MPengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }
}
