<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTraits;


class MProdi extends Model
{
    use HasFactory, UuidTraits;

    protected $table = 'tbl_prodi';
    protected $primaryKey = 'id_prodi';

    protected $fillable = [
        'prodi', // Tambahkan 'prodi' ke sini untuk pengisian massal
    ];
}
