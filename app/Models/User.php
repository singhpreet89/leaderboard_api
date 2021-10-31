<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['game'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'line1',
        'line2',
        'city',
        'province',
        'country',
        'postal_code',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * One to One Relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function game(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Game::class);
    }

    /**
     * Points scope
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPointsCollection($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->leftJoin('games', 'users.id', '=', 'games.user_id')->select('users.*', 'games.points')->orderByDesc('games.points');
    }

    /**
     * Points scope
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int $usderId
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPointsResource(Builder $query, int $userId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->leftJoin('games', 'users.id', '=', 'games.user_id')->select('users.*', 'games.points')->where('user_id', $userId)->orderByDesc('games.points');
    }

    /**
     * Mutator
     *
     * @param  string $email
     * 
     * @return void
     */
    public function setEmailAttribute(string $email): void
    {
        $this->attributes['email'] = Str::lower($email);
    }

    /**
     * Accessor
     *
     * @param  string $name
     * 
     * @return string 
     */
    public function getNameAttribute(string $name): string
    {
        return ucwords($name);
    } 
}
