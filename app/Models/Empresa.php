<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    public function administrador() {
        return User::role('administrador')->where('id_empresa', $this->id)->first();
    }

    public function workers() {
        return $this->hasMany(User::class, 'id_empresa', 'id');
    }

    public function workersCount() {
        return $this::workers()->count();
    }
}
