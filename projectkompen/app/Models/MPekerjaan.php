<?php

namespace App\Models;

use App\Traits\UuidTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MPekerjaan extends Model
{
    use HasFactory, Notifiable, UuidTraits;

    protected $table = 'tbl_pekerjaan';
    protected $primaryKey = 'id_pekerjaan';

    protected $fillable = ['kode_pekerjaan', 'nama_pekerjaan', 'jam_pekerjaan', 'batas_pekerja', 'penanggung_jawab', 'id_penanggung_jawab'];

    public function pengajuans()
    {
        return $this->hasMany(MPengajuan::class, 'kode_pekerjaan', 'kode_pekerjaan');
    }
}
