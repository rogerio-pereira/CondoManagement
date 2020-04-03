<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'tenant_id',
        'apartment_id',
        'maintenance_user_id',
        'problem',
        'solution',
        'solved',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function maintenanceUser()
    {
        return $this->belongsTo(User::class, 'maintenance_user_id');
    }
}
