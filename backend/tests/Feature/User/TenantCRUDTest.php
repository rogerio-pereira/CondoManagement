<?php

namespace Tests\Feature\User;

use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantCRUDTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aAdminUserCanGetAllTenantsOrderedByName()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant2 = factory(User::class)->create([
            'name' => 'Tenant 2',
            'role' => 'Tenant'
        ]);
        $tenant1 = factory(User::class)->create([
            'name' => 'Tenant 1',
        ]);

        $request = $this->get('api/tenants');
        $request->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 3,  //First user is the admin
                    'name' => $tenant1->name,
                    'email' => $tenant1->email,
                    'role' => 'Tenant',
                ],
                [
                    'id' => 2,  //First user is the admin
                    'name' => $tenant2->name,
                    'email' => $tenant2->email,
                    'role' => 'Tenant',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanGetATenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create([
            'name' => 'Tenant 2',
            'role' => 'Tenant'
        ]);

        $request = $this->get('api/tenants/2');
        $request->assertOk()
            ->assertJson(
                [
                    'id' => 2,  //First user is the admin
                    'name' => $tenant->name,
                    'email' => $tenant->email,
                    'role' => 'Tenant',
                ]
            );
    }

    /**
     * @test
     */
    public function aAdminUserCantGetAUserThatIsNotTenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create([
            'name' => 'Tenant 2',
            'role' => 'Admin'
        ]);

        $request = $this->get('api/tenants/2');
        $request->assertStatus(404);
    }

    /**
     * @test
     */
    public function aAdminUserCanCreateATenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'Tenant 2',
            'email' => 'tenant@tenant.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->post('api/tenants', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 2,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => 'Tenant',
            ]);
    }

    /**
     * @test
     */
    public function aUnauthenticatedUserCanCreateATenant()
    {
        $data = [
            'name' => 'Tenant 2',
            'email' => 'tenant@tenant.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->post('api/tenants', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => 'Tenant',
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCantBeCreatedWithoutRequiredFields()
    {
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $request = $this->post('api/tenants', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'email' => [
                        'The email field is required.'
                    ],
                    'password' => [
                        'The password field is required.'
                    ],
                    'password_confirmation' => [
                        'The password confirmation field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCantBeCreatedWithoutValidEmail()
    {
        $data = [
            'name' => 'Tenant',
            'email' => 'tenant',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->post('api/tenants', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
        
        $data['email'] = 'tenant@';
        $request = $this->post('api/tenants', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
    
        $data['email'] = 'tenant@tenant';
        $request = $this->post('api/tenants', $data);
        $request->assertCreated();

        $data['email'] = 'tenant@tenant.com';
        $request = $this->post('api/tenants', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aTenantUserCantBeCreatedWithDifferentPasswordAndConfirmation()
    {
        $data = [
            'name' => 'Tenant',
            'email' => 'tenant@tenant.com',
            'password' => 'secret',
            'password_confirmation' => 'secret2',
        ];

        $request = $this->post('api/tenants', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => [
                        'The password and password confirmation must match.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanUpdateATenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create();

        $request = $this->get('api/tenants');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => $tenant->name,
                    'email' => $tenant->email,
                    'role' => 'Tenant',
                ]
            ]);

        $data = [
            'name' => 'Tenant',
            'email' => 'tenant@tenant.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->put('api/tenants/2', $data);
        $request->assertOk()
            ->assertJson([
                    'id' => 2,
                    'name' => 'Tenant',
                    'email' => 'tenant@tenant.com',
                    'role' => 'Tenant',
            ]);

        $request = $this->get('api/tenants');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => 'Tenant',
                    'email' => 'tenant@tenant.com',
                    'role' => 'Tenant',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanUpdateAUserThatIsNotTenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create([
            'role' => 'Admin'
        ]);

        $request = $this->get('api/tenants');
        $request->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);

        $data = [
            'name' => 'Tenant',
            'email' => 'tenant@tenant.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->put('api/tenants/2', $data);
        $request->assertStatus(404);
    }

    /**
     * @test
     */
    public function aAdminUserCanDeleteATenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create([
            'name' => 'Tenant 2',
            'role' => 'Tenant'
        ]);

        $request = $this->get('api/tenants');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 2,  //First user is the admin
                    'name' => $tenant->name,
                    'email' => $tenant->email,
                    'role' => 'Tenant',
                ]
            ]);

        $request = $this->delete('api/tenants/2');
        $request->assertOk()
            ->assertJson([]);

        $request = $this->get('api/tenants');
        $request->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }

    /**
     * @test
     */
    public function aAdminUserCantDeleteAUserThatIsNotTenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $tenant = factory(User::class)->create([
            'name' => 'Tenant 2',
            'role' => 'Admin'
        ]);

        $request = $this->delete('api/tenants/2');
        $request->assertStatus(404);
    }
}
