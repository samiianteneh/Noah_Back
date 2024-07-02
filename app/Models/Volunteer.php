<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
        use HasFactory, HasUuids;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
