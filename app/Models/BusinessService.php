<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessService extends Model
{
    use HasFactory;
    protected $table = 'business_services';
    protected $fillable = [
        'user_id', // 'user_id' is the foreign key of 'id' in 'users' table
        'name',
        'description',
        'price',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
