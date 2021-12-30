<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $fillable = ['title','genre','author','year','pages','language','edition','publisher','isbn'];

    public function readers(){
        return $this->belongsToMany(Reader::class, 'book_reader');
    }
}
