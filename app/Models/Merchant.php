<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Merchant extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['commission_rate', 'password'];
    protected $hidden = ['password'];

    /**
     * 通过给定的username获取用户实例
     *
     * @param string $username
     * @return Merchant
     */
    public function findForPassport(string $username): Merchant
    {
        return $this->where('username', $username)->first();
    }

    /**
     * @return HasMany
     */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany
     */
    public function queryLog(): HasMany
    {
        return $this->hasMany(QueryLog::class);
    }

    /**
     * @return HasMany
     */
    public function badCustomer(): HasMany
    {
        return $this->hasMany(BadCustomer::class);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
