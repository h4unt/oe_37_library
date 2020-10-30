<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $primaryKey = 'like_id';

    protected $fillable = [
        'book_id',
        'user_id',
    ];

    public function Book()
    {
        return $this->belongsTo(Book::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
