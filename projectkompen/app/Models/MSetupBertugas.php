<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTraits;

class MSetupBertugas extends Model
{
    use HasFactory, UuidTraits;
    protected $table = 'tbl_setup_bertugas';
    protected $primaryKey = 'id_setup_bertugas';

    protected $fillable = [
        'id_user',
        'kode_user',
        'nama_user',
        'role',
        'tgl_betugas',
    ];
}
