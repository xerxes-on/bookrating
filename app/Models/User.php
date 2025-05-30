<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'user_books')
            ->withPivot('status');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function likedQuotes(): BelongsToMany
    {
        return $this->belongsToMany(Quote::class, 'user_quotes');
    }

    public function likedGenres(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_user');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function followingAuthors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_follows', 'user_id', 'author_id');
    }

    public function likedRatings(): BelongsToMany
    {
        return $this->belongsToMany(Rating::class, 'likes');
    }


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
