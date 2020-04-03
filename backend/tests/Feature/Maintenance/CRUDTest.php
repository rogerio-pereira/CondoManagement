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
        $this->withoutExceptionHandling();
        $this->actingAs($this->tenant, 'api');
        $this->createAditionalData();

        $request = $this->get('/api/maintenance/2');
        $request->assertForbidden();
    }
}
