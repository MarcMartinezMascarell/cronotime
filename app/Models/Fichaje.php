<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichaje extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'started_at', 'stopped_at', 'forgot', 'total_time',
    ];

    protected $dates = ['started_at', 'stopped_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
