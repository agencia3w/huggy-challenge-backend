<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    use HasFactory;

    public $fillable = ['name', 'email', 'phone', 'address', 'district', 'state', 'city', 'zipCode', 'birthday'];
}
