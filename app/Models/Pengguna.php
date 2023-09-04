<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'penggunas';

    protected $fillable = [
        'npaidi',
        'nama_lengkap',
        'no_telepon',
        'email',
        'password',
        'ulangi_password',
    ];
}