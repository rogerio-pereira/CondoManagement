<?php

namespace Tests\Feature\Apartment;

use App\Model\User;
use Tests\TestCase;
use App\Model\Apartment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CRUDTest extends TestCase
{
    
    use RefreshDatabase;

    /**
     * @test
     */
    public function aAdminUserCanGetAllApartmentsOrderedByName()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $apartment2 = factory(Apartment::class)->create([
            'name' => 'Apartment 2',
        ]); 
        $apartment1 = factory(Apartment::class)->create([
            'name' => 'Apartment 1',
            'occupied' => true,
            'tenant_id' => 2,
        ]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => $apartment1->name,
                    'price' => $apartment1->price,
                    'occupied' => true,
                    'tenant' => [
                        'id' => 2,
                        'name' => $tenant->name,
                        'email' => $tenant->email,
                        'phone' => $tenant->phone
                    ],
                ],
                [
                    'id' => 1,
                    'name' => $apartment2->name,
                    'price' => $apartment2->price,
                    'occupied' => false,
                    'tenant' => [],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanGetAApartment()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $apartment = factory(Apartment::class)->create([
            'occupied' => true,
            'tenant_id' => 2,
        ]);

        $request = $this->get('api/apartments/1');
        $request->assertOk()
            ->assertJson([
                    'id' => 1,
                    'name' => $apartment->name,
                    'price' => $apartment->price,
                    'occupied' => 1,
                    'tenant_id' => 2,
                    'tenant' => [
                        'id' => 2,
                        'name' => $tenant->name,
                        'email' => $tenant->email,
                        'phone' => $tenant->phone
                    ],
                ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanCreateAApartmentWithoutTenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => false
        ];

        $request = $this->post('api/apartments', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'name' => 'Apartment',
                'price' => 950,
                'occupied' => false,
                'tenant' => [],
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanCreateAApartmentWithTenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => true,
            'tenant_id' => 2
        ];

        $request = $this->post('api/apartments', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'name' => 'Apartment',
                'price' => 950,
                'occupied' => true,
                'tenant' => [
                    'id' => 2,
                    'name' => $tenant->name,
                    'email' => $tenant->email,
                    'phone' => $tenant->phone
                ],
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantCreatedApartmentWithoutRequiredFields()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => '',
            'price' => '',
            'occupied' => '',
        ];

        $request = $this->post('api/apartments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'price' => [
                        'The price field is required.'
                    ],
                    'occupied' => [
                        'The occupied field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantCreatedApartmentWithInvalidPrice()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'Apartment',
            'price' => 'price',
            'occupied' => false,
        ];

        $request = $this->post('api/apartments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'price' => [
                        'The price must be a number.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantCreatedApartmentWithInvalidOcupied()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => 'true',
        ];

        $request = $this->post('api/apartments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'occupied' => [
                        'The occupied field must be true or false.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantCreatedApartmentWithInvalidTenantId()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => true,
            'tenant_id' => 'id'
        ];

        $request = $this->post('api/apartments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'tenant_id' => [
                        'The tenant id must be a number.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantCreatedApartmentWithTenantIdThatDoesntExists()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => true,
            'tenant_id' => 2
        ];

        $request = $this->post('api/apartments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'tenant_id' => [
                        'The selected tenant id is invalid.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantCreatedApartmentWithTenantIdThatIsNotATenant()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => true,
            'tenant_id' => 1
        ];

        $request = $this->post('api/apartments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'tenant_id' => [
                        'The selected user is not a tenant.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanUpdateAApartment()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $apartment = factory(Apartment::class)->create();

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $apartment->name,
                    'price' => $apartment->price,
                    'occupied' => false,
                    'tenant' => [],
                ]
            ]);

        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => true,
            'tenant_id' => 2
        ];

        $request = $this->put('api/apartments/1', $data);
        $request->assertOk()
            ->assertJson([
                    'id' => 1,
                    'name' => 'Apartment',
                    'price' => 950,
                    'occupied' => true,
                    'tenant_id' => 2,
                    'tenant' => [
                        'id' => 2,
                        'name' => $tenant->name,
                        'email' => $tenant->email,
                        'phone' => $tenant->phone
                    ],
            ]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'Apartment',
                    'price' => 950,
                    'occupied' => true,
                    'tenant_id' => 2,
                    'tenant' => [
                        'id' => 2,
                        'name' => $tenant->name,
                        'email' => $tenant->email,
                        'phone' => $tenant->phone
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanUpdateAApartmentWithNoTenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $apartment = factory(Apartment::class)->create([
            'occupied' => true,
            'tenant_id' => 2,
        ]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $apartment->name,
                    'price' => $apartment->price,
                    'occupied' => true,
                    'tenant_id' => 2,
                    'tenant' => [
                        'id' => 2,
                        'name' => $tenant->name,
                        'email' => $tenant->email,
                        'phone' => $tenant->phone
                    ],
                ]
            ]);

        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => false,
            'tenant_id' => 2
        ];

        $request = $this->put('api/apartments/1', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => 'Apartment',
                'price' => 950,
                'occupied' => false,
                'tenant_id' => null,
                'tenant' => [],
            ]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'Apartment',
                    'price' => 950,
                    'occupied' => false,
                    'tenant_id' => null,
                    'tenant' => [],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantUpdateAApartmentWithATenantThatIsNotTenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $apartment = factory(Apartment::class)->create([
            'occupied' => true,
            'tenant_id' => 2,
        ]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $apartment->name,
                    'price' => $apartment->price,
                    'occupied' => true,
                    'tenant_id' => 2,
                    'tenant' => [
                        'id' => 2,
                        'name' => $tenant->name,
                        'email' => $tenant->email,
                        'phone' => $tenant->phone
                    ],
                ]
            ]);

        $data = [
            'name' => 'Apartment',
            'price' => 950,
            'occupied' => true,
            'tenant_id' => 1
        ];

        $request = $this->put('api/apartments/1', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'tenant_id' => [
                        'The selected user is not a tenant.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanDeleteAApartment()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $apartment = factory(Apartment::class)->create([]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $apartment->name,
                    'price' => $apartment->price,
                    'occupied' => false,
                    'tenant' => [],
                ]
            ]);

        $request = $this->delete('api/apartments/1');
        $request->assertOk()
            ->assertJson([]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }

    /**
     * @test
     */
    public function aTenantCanBeDeletedWithoutAffectTheAppartment()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create(['role' => 'Tenant']);
        $apartment = factory(Apartment::class)->create([
            'occupied' => true,
            'tenant_id' => 2,
        ]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $apartment->name,
                    'price' => $apartment->price,
                    'occupied' => true,
                    'tenant_id' => 2,
                    'tenant' => [
                        'id' => 2,
                        'name' => $tenant->name,
                        'email' => $tenant->email,
                        'phone' => $tenant->phone
                    ],
                ]
            ]);

        $request = $this->delete('api/tenants/2');
        $request->assertOk()
            ->assertJson([]);

        $request = $this->get('api/apartments');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $apartment->name,
                    'price' => $apartment->price,
                    'occupied' => false,
                    'tenant_id' => null,
                    'tenant' => [],
                ]
            ]);
    }
}
