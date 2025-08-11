<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'password_confirmation',
        'profile_image',
        'postal_code',
        'address',
        'building',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function purchasedItems()
    {
        return $this->belongsToMany(Item::class, 'purchases', 'user_id', 'item_id');
    }

    public function likes()
    {
        return $this->belongsToMany(Item::class, 'likes')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function receivedRatings()
    {
        return $this->hasMany(Rating::class, 'to_user_id');
    }
}
