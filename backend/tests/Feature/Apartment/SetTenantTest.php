<?php

namespace Tests\Feature\Model;

use App\Model\User;
use App\Model\Apartment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetTenantTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function ApartmentSetTenant()
    {
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $apartment = factory(Apartment::class)->create(['occupied' => false, 'tenant_id' => null]);

        $this->assertDatabaseHas('apartments', [
            'id' => 1,
            'name' => $apartment->name,
            'price' => $apartment->price,
            'occupied' => false,
            'tenant_id' => null,
        ]);

        $apartment->setTenant(1);

        $this->assertDatabaseHas('apartments', [
            'id' => 1,
            'name' => $apartment->name,
            'price' => $apartment->price,
            'occupied' => true,
            'tenant_id' => 1,
        ]);
    }

    /**
     * @test
     */
    public function ApartmentSetEmptyTenant()
    {
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $apartment = factory(Apartment::class)->create(['occupied' => true, 'tenant_id' => 1]);

        $this->assertDatabaseHas('apartments', [
            'id' => 1,
            'name' => $apartment->name,
            'price' => $apartment->price,
            'occupied' => true,
            'tenant_id' => 1,
        ]);

        $apartment->setTenant();

        $this->assertDatabaseHas('apartments', [
            'id' => 1,
            'name' => $apartment->name,
            'price' => $apartment->price,
            'occupied' => false,
            'tenant_id' => null,
        ]);
    }
}
