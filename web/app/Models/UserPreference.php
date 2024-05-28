<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'notification_preference'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
