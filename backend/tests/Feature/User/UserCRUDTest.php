<?php

namespace Tests\Feature\User;

use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCRUDTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aAdminUserCanGetAllUsersOrderedByName()
    {
        $user2 = factory(User::class)->create([
            'name' => 'User 2',
            'role' => 'Maintenance',
        ]);
        $user1 = factory(User::class)->create([
            'name' => 'User 1',
            'role' => 'Admin',
        ]);
        $this->actingAs($user1, 'api');

        $request = $this->get('api/users');
        $request->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2, 
                    'name' => $user1->name,
                    'email' => $user1->email,
                    'role' => 'Admin',
                    'phone' => $user1->phone,
                ],
                [
                    'id' => 1, 
                    'name' => $user2->name,
                    'email' => $user2->email,
                    'phone' => $user2->phone,
                    'role' => 'Maintenance',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanGetAUser()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $user = factory(User::class)->create([
            'name' => 'User 2',
            'role' => 'Admin'
        ]);

        $request = $this->get('api/users/2');
        $request->assertOk()
            ->assertJson(
                [
                    'id' => 2,  //First user is the admin
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => 'Admin',
                ]
            );
    }

    /**
     * @test
     */
    public function aAdminUserCantGetAUserThatIsNotUser()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $user = factory(User::class)->create([
            'name' => 'User 2',
            'role' => 'Tenant'
        ]);

        $request = $this->get('api/users/2');
        $request->assertStatus(404);
    }

    /**
     * @test
     */
    public function aAdminUserCanCreateAUser()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'User 2',
            'email' => 'user@user.com',
            'phone' => '(999) 999-9999',
            'role' => 'Admin',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->post('api/users', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 2,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'role' => 'Admin',
            ]);
    }

    /**
     * @test
     */
    public function aUserUserCantBeCreatedWithoutRequiredFields()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => '',
            'email' => '',
            'phone' => '',
            'role' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $request = $this->post('api/users', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'email' => [
                        'The email field is required.'
                    ],
                    'phone' => [
                        'The phone field is required.'
                    ],
                    'role' => [
                        'The role field is required.'
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
    public function aUserCantBeCreatedWithoutWrongRole()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'User',
            'email' => 'user@user.com',
            'phone' => '(999) 999-9999',
            'role' => 'A',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->post('api/users', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'role' => [
                        'The selected role is invalid.'
                    ],
                ]
            ]);
    
        $data['role'] = 'Admin';
        $data['email'] = 'user2@user.com';
        $request = $this->post('api/users', $data);
        $request->assertCreated();

        $data['role'] = 'Maintenance';
        $data['email'] = 'user3@user.com';
        $request = $this->post('api/users', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantBeCreatedWithoutValidEmail()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'User',
            'email' => 'user',
            'phone' => '(999) 999-9999',
            'role' => 'Admin',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->post('api/users', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
        
        $data['email'] = 'user@';
        $request = $this->post('api/users', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
    
        $data['email'] = 'user@user';
        $request = $this->post('api/users', $data);
        $request->assertCreated();

        $data['email'] = 'user@user.com';
        $request = $this->post('api/users', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserUserCantBeCreatedWithDifferentPasswordAndConfirmation()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $data = [
            'name' => 'User',
            'email' => 'user@user.com',
            'phone' => '(999) 999-9999',
            'role' => 'Admin',
            'password' => 'secret',
            'password_confirmation' => 'secret2',
        ];

        $request = $this->post('api/users', $data);
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
    public function aAdminUserCanUpdateAUser()
    {
        $user = factory(User::class)->create(['role' => 'Admin']);
        $this->actingAs($user, 'api');

        $request = $this->get('api/users');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => 'Admin',
                ]
            ]);

        $data = [
            'name' => 'User',
            'email' => 'user@user.com',
            'phone' => '(999) 999-9999',
            'role' => 'Maintenance',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->put('api/users/1', $data);
        $request->assertOk()
            ->assertJson([
                    'id' => 1,
                    'name' => 'User',
                    'email' => 'user@user.com',
                    'role' => 'Maintenance',
                    'phone' => '(999) 999-9999',
            ]);

        $request = $this->get('api/users');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'User',
                    'email' => 'user@user.com',
                    'role' => 'Maintenance',
                    'phone' => '(999) 999-9999',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanUpdateAUserWithoutPassword()
    {
        $user = factory(User::class)->create(['role' => 'Admin']);
        $this->actingAs($user, 'api');
        
        $request = $this->get('api/users');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => 'Admin',
                    'phone' => $user->phone,
                ]
            ]);

        $data = [
            'name' => 'User',
            'email' => 'user@user.com',
            'phone' => '(999) 999-9999',
            'role' => 'Maintenance',
        ];

        $request = $this->put('api/users/1', $data);
        $request->assertOk()
            ->assertJson([
                    'id' => 1,
                    'name' => 'User',
                    'email' => 'user@user.com',
                    'role' => 'Maintenance',
                    'phone' => '(999) 999-9999',
            ]);

        $request = $this->get('api/users');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'User',
                    'email' => 'user@user.com',
                    'role' => 'Maintenance',
                    'phone' => '(999) 999-9999',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantUpdateATenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $user = factory(User::class)->create([
            'role' => 'Tenant'
        ]);

        $request = $this->get('api/users');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJsonMissing([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
            ]);

        $data = [
            'name' => 'User',
            'email' => 'user@user.com',
            'phone' => '(999) 999-9999',
            'role' => 'Maintenance',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $request = $this->put('api/users/2', $data);
        $request->assertStatus(404);
    }

    /**
     * @test
     */
    public function aAdminUserCanDeleteAUser()
    {
        $user2 = factory(User::class)->create([
            'name' => 'User 2',
            'role' => 'Maintenance',
        ]);
        $user1 = factory(User::class)->create([
            'name' => 'User 1',
            'role' => 'Admin',
        ]);
        $this->actingAs($user1, 'api');

        $request = $this->get('api/users');
        $request->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2, 
                    'name' => $user1->name,
                    'email' => $user1->email,
                    'role' => 'Admin',
                    'phone' => $user1->phone,
                ],
                [
                    'id' => 1, 
                    'name' => $user2->name,
                    'email' => $user2->email,
                    'phone' => $user2->phone,
                    'role' => 'Maintenance',
                ]
            ]);

        $request = $this->delete('api/users/1');
        $request->assertOk()
            ->assertJson([]);

        $request = $this->get('api/users');
        $request->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 2, 
                    'name' => $user1->name,
                    'email' => $user1->email,
                    'role' => 'Admin',
                    'phone' => $user1->phone,
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantDeleteATenant()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Admin']), 'api');
        $user = factory(User::class)->create([
            'role' => 'Tenant',
        ]);

        $request = $this->delete('api/users/2');
        $request->assertStatus(404);
    }
}
