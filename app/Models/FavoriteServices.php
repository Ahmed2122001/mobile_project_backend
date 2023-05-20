<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteServices extends Model
{
    use HasFactory;
    protected $table = 'favorite_services';
    protected $fillable = [
        'user_id', // 'user_id' is the foreign key of 'id' in 'users' table
        'business_service_id', // 'business_service_id' is the foreign key of 'id' in 'business_services' table
        'is_favorite'

    ];
    public function businessService()
    {
        return $this->belongsTo(BusinessService::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
