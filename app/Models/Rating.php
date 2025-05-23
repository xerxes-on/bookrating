<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rating extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [];
    protected $table = 'ratings';

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function isLikedByCurrentUser(): bool
    {
        $user = auth()->user();
        return $this->likedByUsers()
            ->where('user_id', $user->id)
            ->exists();
    }
    protected $appends = ['is_liked'];
    public function getIsLikedAttribute(): bool
    {
        return $this->isLikedByCurrentUser();
    }

}
