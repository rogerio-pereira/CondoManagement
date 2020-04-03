<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable = [
        'name',
        'price',
        'occupied',
        'tenant_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function setTenant($tenant_id = null)
    {
        $this->occupied = isset($tenant_id);
        $this->tenant_id = $tenant_id;
        $this->save();
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
