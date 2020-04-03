<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'apartment_id',
        'tenant_id',
        'value',
        'due_at',
        'payed',
    ];

    protected $dates = [
        'due_at'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'due_at' => 'datetime:Y-m-d',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
}
