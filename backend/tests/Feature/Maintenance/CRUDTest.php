<?php

namespace Tests\Feature\Maintenance;

use App\Model\User;
use Tests\TestCase;
use App\Model\Apartment;
use App\Model\Maintenance;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CRUDTest extends TestCase
{
    use RefreshDatabase;

    private $admin;                 //ID - 1
    private $maintenance;           //ID - 2
    private $tenant;                //ID - 3
    private $apartment;             //ID - 1
    private $maintenanceRequest;    //ID - 1

    private $tenant2;               //ID - 4
    private $apartment2;            //ID - 2
    private $maintenanceRequest2;    //ID - 2
    private $maintenanceRequest3;    //ID - 3

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = factory(User::class)->create(['role' => 'Admin']);
        $this->maintenance = factory(User::class)->create(['role' => 'Maintenance']);
        $this->tenant = factory(User::class)->create(['role' => 'Tenant']);
        $this->apartment = factory(Apartment::class)->create([
                                'occupied' => true,
                                'tenant_id' => 3
                            ]);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
                                        'tenant_id' => '3', 
                                        'apartment_id' => 1,
                                        'maintenance_user_id' => 2,
                                        'solution' => 'Solution',
                                        'solved' => false,
                                    ]);
    }

    public function createAditionalData()
    {
        $this->tenant2 = factory(User::class)->create(['role' => 'Tenant']);
        $this->apartment2 = factory(Apartment::class)->create([
            'occupied' => true,
            'tenant_id' => 4
        ]);
        $this->maintenanceRequest2 = factory(Maintenance::class)->create(['tenant_id' => '4', 'apartment_id' => 2]);
        $this->maintenanceRequest3 = factory(Maintenance::class)->create(['tenant_id' => '3', 'apartment_id' => 1]);
    }

    /**
     * @test
     */
    public function AAdminUserCanGetAllMaintenanceRequests()
    {
        $this->actingAs($this->admin, 'api');
        $this->createAditionalData();

        $request = $this->get('/api/maintenance');
        $request->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 1,
                    'tenant_id' => $this->tenant->id,
                    'apartment_id' => $this->apartment->id,
                    'maintenance_user_id' => 2,
                    'problem' => $this->maintenanceRequest->problem,
                    'solution' => $this->maintenanceRequest->solution,
                    'solved' => $this->maintenanceRequest->solved,
                    'tenant' => [
                        'id' => 3,
                        'name' => $this->tenant->name,
                    ],
                    'apartment' => [
                        'id' => 1,
                        'name' => $this->apartment->name,
                    ],
                    'maintenance_user' => [
                        'id' => 2,
                        'name' => $this->maintenance->name,
                    ],
                ],
                [
                    'id' => 2,
                    'tenant_id' => $this->tenant2->id,
                    'apartment_id' => $this->apartment2->id,
                    'maintenance_user_id' => null,
                    'problem' => $this->maintenanceRequest2->problem,
                    'solution' => '',
                    'solved' => false,
                    'tenant' => [
                        'id' => 4,
                        'name' => $this->tenant2->name,
                    ],
                    'apartment' => [
                        'id' => 2,
                        'name' => $this->apartment2->name,
                    ],
                    'maintenance_user' => [],
                ],
                [
                    'id' => 3,
                    'tenant_id' => $this->tenant->id,
                    'apartment_id' => $this->apartment->id,
                    'maintenance_user_id' => null,
                    'problem' => $this->maintenanceRequest3->problem,
                    'solution' => '',
                    'solved' => false,
                    'tenant' => [
                        'id' => 3,
                        'name' => $this->tenant->name,
                    ],
                    'apartment' => [
                        'id' => 1,
                        'name' => $this->apartment->name,
                    ],
                    'maintenance_user' => [],
                ],
            ]);
    }

    /**
     * @test
     */
    public function AMaintenanceUserCanGetAllMaintenanceRequests()
    {
        $this->actingAs($this->maintenance, 'api');
        $this->createAditionalData();

        $request = $this->get('/api/maintenance');
        $request->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 1,
                    'tenant_id' => $this->tenant->id,
                    'apartment_id' => $this->apartment->id,
                    'maintenance_user_id' => 2,
                    'problem' => $this->maintenanceRequest->problem,
                    'solution' => $this->maintenanceRequest->solution,
                    'solved' => $this->maintenanceRequest->solved,
                    'tenant' => [
                        'id' => 3,
                        'name' => $this->tenant->name,
                    ],
                    'apartment' => [
                        'id' => 1,
                        'name' => $this->apartment->name,
                    ],
                    'maintenance_user' => [
                        'id' => 2,
                        'name' => $this->maintenance->name,
                    ],
                ],
                [
                    'id' => 2,
                    'tenant_id' => $this->tenant2->id,
                    'apartment_id' => $this->apartment2->id,
                    'maintenance_user_id' => null,
                    'problem' => $this->maintenanceRequest2->problem,
                    'solution' => '',
                    'solved' => false,
                    'tenant' => [
                        'id' => 4,
                        'name' => $this->tenant2->name,
                    ],
                    'apartment' => [
                        'id' => 2,
                        'name' => $this->apartment2->name,
                    ],
                    'maintenance_user' => [],
                ],
                [
                    'id' => 3,
                    'tenant_id' => $this->tenant->id,
                    'apartment_id' => $this->apartment->id,
                    'maintenance_user_id' => null,
                    'problem' => $this->maintenanceRequest3->problem,
                    'solution' => '',
                    'solved' => false,
                    'tenant' => [
                        'id' => 3,
                        'name' => $this->tenant->name,
                    ],
                    'apartment' => [
                        'id' => 1,
                        'name' => $this->apartment->name,
                    ],
                    'maintenance_user' => [],
                ],
            ]);
    }

    /**
     * @test
     */
    public function ATenantUserUserCanGetHisMaintenanceRequests()
    {
        $this->actingAs($this->tenant, 'api');
        $this->createAditionalData();

        $request = $this->get('/api/maintenance');
        $request->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 1,
                    'tenant_id' => $this->tenant->id,
                    'apartment_id' => $this->apartment->id,
                    'maintenance_user_id' => 2,
                    'problem' => $this->maintenanceRequest->problem,
                    'solution' => $this->maintenanceRequest->solution,
                    'solved' => $this->maintenanceRequest->solved,
                    'tenant' => [
                        'id' => 3,
                        'name' => $this->tenant->name,
                    ],
                    'apartment' => [
                        'id' => 1,
                        'name' => $this->apartment->name,
                    ],
                    'maintenance_user' => [
                        'id' => 2,
                        'name' => $this->maintenance->name,
                    ],
                ],
                [
                    'id' => 3,
                    'tenant_id' => $this->tenant->id,
                    'apartment_id' => $this->apartment->id,
                    'maintenance_user_id' => null,
                    'problem' => $this->maintenanceRequest3->problem,
                    'solution' => '',
                    'solved' => false,
                    'tenant' => [
                        'id' => 3,
                        'name' => $this->tenant->name,
                    ],
                    'apartment' => [
                        'id' => 1,
                        'name' => $this->apartment->name,
                    ],
                    'maintenance_user' => [],
                ],
            ]);
    }

    /**
     * @test
     */
    public function AAdminUserCanGetAMaintenanceRequests()
    {
        $this->actingAs($this->admin, 'api');

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => $this->maintenanceRequest->solved,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function AMaintenanceUserCanGetAMaintenanceRequests()
    {
        $this->actingAs($this->maintenance, 'api');

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => $this->maintenanceRequest->solved,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function ATenantUserCanGetHisMaintenanceRequests()
    {
        $this->actingAs($this->tenant, 'api');

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => $this->maintenanceRequest->solved,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function ATenantUserCantGetOthersMaintenanceRequests()
    {
        $this->actingAs($this->tenant, 'api');
        $this->createAditionalData();

        $request = $this->get('/api/maintenance/2');
        $request->assertForbidden();
    }

    /**
     * @test
     */
    public function aMaintenanceUserCanSetMaintenanceRequestAsSolved()
    {
        $this->actingAs($this->maintenance, 'api');

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => $this->maintenanceRequest->solved,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);

        $request = $this->put('/api/maintenance/1/solved');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
            ]);

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanSetMaintenanceRequestAsSolved()
    {
        $this->actingAs($this->admin, 'api');

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => $this->maintenanceRequest->solved,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);

        $request = $this->put('/api/maintenance/1/solved');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
            ]);

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCanSetHisMaintenanceRequestAsSolved()
    {
        $this->actingAs($this->tenant, 'api');

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => false,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);

        $request = $this->put('/api/maintenance/1/solved');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
            ]);

        $request = $this->get('/api/maintenance/1');
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => 2,
                'problem' => $this->maintenanceRequest->problem,
                'solution' => $this->maintenanceRequest->solution,
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => 2,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCantSetOthersMaintenanceRequestAsSolved()
    {
        $this->actingAs($this->tenant, 'api');
        $this->createAditionalData();

        $request = $this->put('/api/maintenance/2/solved');
        
        $request->assertForbidden();
    }
    
    /**
     * @test
     */
    public function aAdminUserCanCreateAMaintenanceRequest()
    {
        $this->actingAs($this->admin, 'api');
        $data = [
            'problem' => 'Problem',
            'tenant_id' => 3,
            'apartment_id' => 1,
        ];

        $request = $this->post('/api/maintenance', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 2,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => null,
                'problem' => 'Problem',
                'solution' => null,
                'solved' => false,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [],
            ]);
    }
    
    /**
     * @test
     */
    public function aMaintenanceUserCantCreateAMaintenanceRequest()
    {
        $this->actingAs($this->maintenance, 'api');
        $data = [
            'problem' => 'Problem',
            'tenant_id' => 3,
            'apartment_id' => 1,
        ];

        $request = $this->post('/api/maintenance', $data);
        $request->assertForbidden();
    }
    
    /**
     * @test
     */
    public function aTenantCanCreateAMaintenanceRequest()
    {
        $this->actingAs($this->tenant, 'api');
        $data = [
            'problem' => 'Problem'
        ];

        $request = $this->post('/api/maintenance', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 2,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => null,
                'problem' => 'Problem',
                'solution' => null,
                'solved' => false,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [],
            ]);
    }

    /**
     * @test
     */
    public function ATenantCanCreateAMaintenanceRequestOnlyForHimSelf()
    {
        $this->actingAs($this->tenant, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);

        $data = [
            'problem' => 'Problem',
            'tenant_id' => 2,
            'apartment_id' => 2
        ];

        $request = $this->post('/api/maintenance', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 2,
                'tenant_id' => $this->tenant->id,
                'apartment_id' => $this->apartment->id,
                'maintenance_user_id' => null,
                'problem' => 'Problem',
                'solution' => null,
                'solved' => false,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [],
            ]);
    }

    /**
     * @test
     */
    public function AAdminCantCreateAMaintenanceRequestWithoutRequiredFields()
    {
        $this->actingAs($this->admin, 'api');

        $data = [
            'problem' => null,
            'tenant_id' => null,
            'apartment_id' => null
        ];

        $request = $this->post('/api/maintenance', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'problem' => [
                        'The problem field is required.'
                    ],
                    'tenant_id' => [
                        'The tenant id field is required.'
                    ],
                    'apartment_id' => [
                        'The apartment id field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function AAdminCantCreateAMaintenanceRequestWithWrongNumericFields()
    {
        $this->actingAs($this->admin, 'api');

        $data = [
            'problem' => 'Problem',
            'tenant_id' => 'tenant',
            'apartment_id' => 'apartment'
        ];

        $request = $this->post('/api/maintenance', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'tenant_id' => [
                        'The tenant id must be a number.'
                    ],
                    'apartment_id' => [
                        'The apartment id must be a number.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function AAdminCantCreateAMaintenanceRequestWithInvalidTenantAndApartment()
    {
        $this->actingAs($this->admin, 'api');

        $data = [
            'problem' => 'Problem',
            'tenant_id' => 100,
            'apartment_id' => 100
        ];

        $request = $this->post('/api/maintenance', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'tenant_id' => [
                        'The selected tenant id is invalid.'
                    ],
                    'apartment_id' => [
                        'The selected apartment id is invalid.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function ATenantCantCreateAMaintenanceRequestWithoutRequiredFields()
    {
        $this->actingAs($this->admin, 'api');

        $data = [
            'problem' => null,
        ];

        $request = $this->post('/api/maintenance', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'problem' => [
                        'The problem field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanUpdateAMaintenance()
    {
        $this->actingAs($this->admin, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'problem' => 'Problem Updated',
            'tenant_id' => 4,
            'apartment_id' => 2,

            'solution' => 'Solution',
            'maintenance_user_id' => '2',
            'solved' => true,
        ];

        $this->assertDatabaseHas('maintenances', [
            'id' => 2,
            'tenant_id' => 3,
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false,
        ]);

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => 2,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => $this->maintenance->id,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCanUpdateAMaintenanceWithoutFieldsThatNeverChange()
    {
        $this->actingAs($this->admin, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            // 'problem' => 'Problem Updated',
            // 'tenant_id' => 4,
            // 'apartment_id' => 2,

            'solution' => 'Solution',
            'maintenance_user_id' => '2',
            'solved' => true,
        ];

        $this->assertDatabaseHas('maintenances', [
            'id' => 2,
            'tenant_id' => 3,
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false,
        ]);

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => 2,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => $this->maintenance->id,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantUpdateAMaintenanceWithoutRequiredFields()
    {
        $this->actingAs($this->admin, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'solution' => null,
            'maintenance_user_id' => null,
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'maintenance_user_id' => [
                        'The maintenance user id field is required when solved is true.'
                    ],
                    'solution' => [
                        'The solution field is required when solved is true.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantUpdateAMaintenanceWithInvalidMaintenanceUserId()
    {
        $this->actingAs($this->admin, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'solution' => 'Solution',
            'maintenance_user_id' => 'maintenance_user_id',
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'maintenance_user_id' => [
                        'The maintenance user id must be a number.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aAdminUserCantUpdateAMaintenanceWithWrongMaintenanceUserId()
    {
        $this->actingAs($this->admin, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'solution' => 'Solution',
            'maintenance_user_id' => 100,
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'maintenance_user_id' => [
                        'The selected maintenance user id is invalid.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aMaintenanceUserCanUpdateAMaintenance()
    {
        $this->actingAs($this->maintenance, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'problem' => 'Problem Updated',
            'tenant_id' => 4,
            'apartment_id' => 2,

            'solution' => 'Solution',
            // 'maintenance_user_id' => '2',
            'solved' => true,
        ];

        $this->assertDatabaseHas('maintenances', [
            'id' => 2,
            'tenant_id' => 3,
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false,
        ]);

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => 2,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => $this->maintenance->id,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aMaintenanceUserCanUpdateAMaintenanceWithoutFieldsThatNeverChange()
    {
        $this->actingAs($this->maintenance, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            // 'problem' => 'Problem Updated',
            // 'tenant_id' => 4,
            // 'apartment_id' => 2,

            'solution' => 'Solution',
            // 'maintenance_user_id' => '2',
            'solved' => true,
        ];

        $this->assertDatabaseHas('maintenances', [
            'id' => 2,
            'tenant_id' => 3,
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false,
        ]);

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => 2,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => $this->maintenance->id,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aMaintenanceUserCantUpdateAMaintenanceWithoutRequiredFields()
    {
        $this->actingAs($this->maintenance, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'solution' => null,
            // 'maintenance_user_id' => null,
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'solution' => [
                        'The solution field is required when solved is true.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aMaintenanceUserCanUpdateAMaintenanceWithoutMaintenanceUserIdField()
    {
        $this->actingAs($this->maintenance, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        factory(User::class)->create(['role' => 'Maintenance']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'solution' => 'Solution',
            'maintenance_user_id' => null,
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);$request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => 2,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => $this->maintenance->id,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aMaintenanceUserCanUpdateAMaintenanceWithoutValidUserIdField()
    {
        $this->actingAs($this->maintenance, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        factory(User::class)->create(['role' => 'Maintenance']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'solution' => 'Solution',
            'maintenance_user_id' => 'maintenance_user_id',
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);$request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => 2,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => $this->maintenance->id,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aMaintenanceUserCanUpdateAMaintenanceWithInValidUserIdField()
    {
        $this->actingAs($this->maintenance, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        factory(User::class)->create(['role' => 'Maintenance']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'solution' => 'Solution',
            'maintenance_user_id' => 100,
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);$request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => 2,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [
                    'id' => $this->maintenance->id,
                    'name' => $this->maintenance->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCanUpdateAMaintenance()
    {
        $this->actingAs($this->tenant, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'problem' => 'Problem Updated',
            'tenant_id' => 4,
            'apartment_id' => 2,

            'solution' => 'Solution',
            'solved' => true,
        ];

        $this->assertDatabaseHas('maintenances', [
            'id' => 2,
            'tenant_id' => 3,
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false,
        ]);

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => null,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [],
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCanUpdateAMaintenanceWithoutFieldsThatNeverChange()
    {
        $this->actingAs($this->tenant, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            // 'problem' => 'Problem Updated',
            // 'tenant_id' => 4,
            // 'apartment_id' => 2,

            'solution' => 'Solution',
            'solved' => true,
        ];

        $this->assertDatabaseHas('maintenances', [
            'id' => 2,
            'tenant_id' => 3,
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false,
        ]);

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 2,
                'tenant_id' => 3,
                'apartment_id' => 1,
                'problem' => 'Problem',

                'maintenance_user_id' => null,
                'solution' => 'Solution',
                'solved' => true,
                'tenant' => [
                    'id' => 3,
                    'name' => $this->tenant->name,
                ],
                'apartment' => [
                    'id' => 1,
                    'name' => $this->apartment->name,
                ],
                'maintenance_user' => [],
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCantUpdateAMaintenanceWithoutRequiredFields()
    {
        $this->actingAs($this->tenant, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'solution' => null,
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'solution' => [
                        'The solution field is required when solved is true.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCantSetAMaintenanceUser()
    {
        $this->actingAs($this->tenant, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => '3', 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'maintenance_user_id' => '2',
            'solution' => 'Solution',
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertStatus(403)
            ->assertJson([
                'errors' => [
                    'maintenance_user_id' => [
                        "A tenant can't set a maintenance user"
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aTenantUserCantUpdateOtherUserMaintenance()
    {
        $this->actingAs($this->tenant, 'api');
        factory(Apartment::class)->create();
        factory(User::class)->create(['role' => 'Tenant']);
        $this->maintenanceRequest = factory(Maintenance::class)->create([
            'tenant_id' => 4, 
            'apartment_id' => 1,
            'maintenance_user_id' => null,
            'problem' => 'Problem',
            'solution' => null,
            'solved' => false
        ]);

        $data = [
            'maintenance_user_id' => '2',
            'solution' => 'Solution',
            'solved' => true,
        ];

        $request = $this->put('/api/maintenance/2', $data);
        $request->assertForbidden();
    }

    /**
     * @test
     */
    public function aAdminUserCantDeleteAMaintenanceRequest()
    {
        $this->actingAs($this->admin, 'api');
        
        $request = $this->delete('/api/maintenance/1');
        $request->assertForbidden();
    }

    /**
     * @test
     */
    public function aMaintenanceUserCantDeleteAMaintenanceRequest()
    {
        $this->actingAs($this->maintenance, 'api');
        
        $request = $this->delete('/api/maintenance/1');
        $request->assertForbidden();
    }

    /**
     * @test
     */
    public function aTenantUserCantDeleteAMaintenanceRequest()
    {
        $this->actingAs($this->tenant, 'api');
        
        $request = $this->delete('/api/maintenance/1');
        $request->assertForbidden();
    }
}
