<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Exceptions\HttpResponseException;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Scope a query to only include tenant users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTenant($query)
    {
        return $query->where('role', 'Tenant');
    }

    /**
     * Scope a query to only include tenant users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUser($query)
    {
        return $query->where('role', '<>', 'Tenant');
    }

    public function apartment()
    {
        return $this->hasOne(Apartment::class, 'tenant_id');
    }

    public function isTenant()
    {
        return $this->role == 'Tenant';
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'tenant_id');
    }

    public static function validateTenant($userId)
    {
        $user = User::find($userId);

        if(!$user->isTenant()) {
            $errors = ['tenant_id' => ['The selected user is not a tenant.']];
            throw new HttpResponseException(response()->json(['errors' => $errors], 422));
        }
    }

    public function maintenances()
    {
        if($this->role == 'Maintenance')
            return $this->hasMany(Maintenance::class, 'maintenance_user_id', 'id');
        else if($this->role == 'Tenant')
            return $this->hasMany(Maintenance::class, 'tenant_id', 'id');
    }
}
