<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MBebasKompen extends Model
{
    use HasFactory;
    protected $table = 'tbl_form_bebas_kompen';
    protected $primaryKey = 'id_bebas_kompen';

    protected $fillable = [
        'id_pengajuan',
        'kode_user',
        'nama_user',
        'kelas',
        'semester',
        'jumlah_terlambat',
        'jumlah_alfa',
        'total',
        'sisa',
        'form_bebas_kompen',
        'status_approval1',
        'approval1_by',
        'status_approval2',
        'approval2_by',
        'status_approval3',
        'approval3_by',



    ];
}
