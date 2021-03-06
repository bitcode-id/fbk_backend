<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'periode';
    protected $fillable = [
      'periode',
      'tahun',
      'keterangan',
      'status'
    ];

    public function user()
    {
      return $this->hasMany('App\Models\User');
    }
}
