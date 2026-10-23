<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmelJawatan extends Model
{
    /** @use HasFactory<\Database\Factories\EmelJawatanFactory> */
    use HasFactory;

    protected $table = 'emel_jawatan';

    protected $fillable = [
        'kod',
        'nama_jawatan',
        'email',
        'susunan',
    ];
}
