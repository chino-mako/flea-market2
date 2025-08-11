<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'brand_name',
        'image_path',
        'condition',
        'description',
        'price',
        'is_sold',
        'is_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function purchases()
    {
        return $this->hasOne(Purchase::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function unreadMessagesCountForUser($userId)
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function scopeTradingForUser($query, $userId)
    {
        return $query->whereHas('messages', function ($q) use ($userId) {
            $q->where('user_id', '!=', $userId);
        })->where('user_id', $userId) // 出品していて誰かがコメントしてる
        ->orWhereHas('messages', function ($q) use ($userId) {
            $q->where('user_id', $userId); // 自分が購入者としてコメントした
        });
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }
}
