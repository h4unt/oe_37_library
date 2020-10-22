<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrow extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'borr_id';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'book_id',
        'borr_status',
        'borrow_date',
        'return_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
