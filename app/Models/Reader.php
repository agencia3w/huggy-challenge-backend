<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Reader extends Model
{
    use HasFactory;

    public $fillable = ['name', 'email', 'phone', 'address', 'district', 'state', 'city', 'zipCode', 'birthday', 'crm_reader_id'];

    public $casts = [
        'birthday' => 'date'
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_reader');
    }

    public static function allFromCache()
    {
        return Cache::remember('readedTotal', 60 * 60, function () {
            return Reader::withCount('books')
                ->orderBy('name')
                ->get();
        });
    }
}
