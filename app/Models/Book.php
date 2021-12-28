<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // protected $cast = [
    //     'publisher' => 'json'
    // ];

    public $fillable = ['title','genre','author','year','pages','language','edition','publisher','isbn'];
}
