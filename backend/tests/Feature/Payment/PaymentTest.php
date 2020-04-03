<?php

namespace Tests\Feature\Payment;

use App\Model\User;
use Tests\TestCase;
use App\Model\Apartment;
use App\Model\Payment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $tenant;
    private $apartment;

    public function setUp() : void 
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['role' => 'Admin']);
        $this->tenant = factory(User::class)->create(['role' => 'Tenant']);
        $this->apartment = factory(Apartment::class)->create();
    }

    private function createPayments() 
    {
        //Tenant 2
        factory(User::class)->create(['role' => 'Tenant']);
        //Apartment 2
        factory(Apartment::class)->create();
        //Payments Apartment 2
        factory(Payment::class)->create([
            'apartment_id' => 2,
            'tenant_id' => 3,
            'due_at' => Carbon::now()->startOfMonth()->toDateString(),
            'payed' => true
        ]);
        factory(Payment::class)->create([
            'apartment_id' => 2,
            'tenant_id' => 3,
            'due_at' => Carbon::now()->startOfMonth()->addMonths(1)->toDateString(),
        ]);
        factory(Payment::class)->create([
            'apartment_id' => 2,
            'tenant_id' => 3,
            'due_at' => Carbon::now()->startOfMonth()->addMonths(2)->toDateString(),
        ]);
        //Payments Apartment 1
        factory(Payment::class)->create([
            'apartment_id' => 1,
            'tenant_id' => 2,
            'due_at' => Carbon::now()->startOfMonth()->toDateString(),
            'payed' => true
        ]);
        factory(Payment::class)->create([
            'apartment_id' => 1,
            'tenant_id' => 2,
            'due_at' => Carbon::now()->startOfMonth()->addMonths(1)->toDateString(),
        ]);
        factory(Payment::class)->create([
            'apartment_id' => 1,
            'tenant_id' => 2,
            'due_at' => Carbon::now()->startOfMonth()->addMonths(2)->toDateString(),
        ]);
    }

    /**
     * @test
     */
    public function AAdminUserCanGetAllPayments()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user, 'api');
        $this->createPayments();

        $request = $this->get('api/payments');
        $request->assertOk()
            ->assertJsonCount(6)
            ->assertJson([
                [
                    'id' => 1,
                    'apartment_id' => 2,
                    'tenant_id' => 3,
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'payed' => true
                ],
                [
                    'id' => 2,
                    'apartment_id' => 2,
                    'tenant_id' => 3,
                    'due_at' => Carbon::now()->startOfMonth()->addMonths(1)->toDateString(),
                    'payed' => false
                ],
                [
                    'id' => 3,
                    'apartment_id' => 2,
                    'tenant_id' => 3,
                    'due_at' => Carbon::now()->startOfMonth()->addMonths(2)->toDateString(),
                    'payed' => false
                ],
                [
                    'id' => 4,
                    'apartment_id' => 1,
                    'tenant_id' => 2,
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'payed' => true
                ],
                [
                    'id' => 5,
                    'apartment_id' => 1,
                    'tenant_id' => 2,
                    'due_at' => Carbon::now()->startOfMonth()->addMonths(1)->toDateString(),
                    'payed' => false
                ],
                [
                    'id' => 6,
                    'apartment_id' => 1,
                    'tenant_id' => 2,
                    'due_at' => Carbon::now()->startOfMonth()->addMonths(2)->toDateString(),
                    'payed' => false
                ]
            ]);
    }

    /**
     * @test
     */
    public function ATenantUserCanGetAllHisPayments()
    {
        $this->actingAs($this->tenant, 'api');
        $this->createPayments();

        $request = $this->get('api/payments');
        $request->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 4,
                    'apartment_id' => 1,
                    'tenant_id' => 2,
                    'due_at' => Carbon::now()->startOfMonth()->toDateString(),
                    'payed' => true
                ],
                [
                    'id' => 5,
                    'apartment_id' => 1,
                    'tenant_id' => 2,
                    'due_at' => Carbon::now()->startOfMonth()->addMonths(1)->toDateString(),
                    'payed' => false
                ],
                [
                    'id' => 6,
                    'apartment_id' => 1,
                    'tenant_id' => 2,
                    'due_at' => Carbon::now()->startOfMonth()->addMonths(2)->toDateString(),
                    'payed' => false
                ]
            ]);
    }

    /**
     * @test
     */
    public function AAdminUserCanCreatePayments()
    {
        $this->actingAs($this->user, 'api');
        $data = [
            'apartment_id' => $this->apartment->id,
            'tenant_id' => $this->tenant->id, //Tenant
            'installments' => 2,
            'start_date' => Carbon::now()->addMonth()->startOfMonth()->toDateString(),
        ];

        $response = $this->post('/api/payments', $data);

        $response->assertCreated()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 1,
                    'apartment_id' => $this->apartment->id,
                    'tenant_id' => $this->tenant->id,
                    'value' => $this->apartment->price,
                    'due_at' => Carbon::now()->addMonth()->startOfMonth()->toDateString(),
                    'payed' => false,
                    'apartment' => [
                        'id' => $this->apartment->id,
                        'name' => $this->apartment->name,
                        'price' => $this->apartment->price,
                        'occupied' => true,
                        'tenant_id' => $this->tenant->id,
                    ],
                    'tenant' => [
                        'id' => $this->tenant->id,
                        'name' => $this->tenant->name,
                        'email' => $this->tenant->email,
                        'phone' => $this->tenant->phone,
                        'role' => 'Tenant'
                    ]
                ],
                [
                    'id' => 2,
                    'apartment_id' => $this->apartment->id,
                    'tenant_id' => $this->tenant->id,
                    'value' => $this->apartment->price,
                    'due_at' => Carbon::now()->addMonth(2)->startOfMonth()->toDateString(),
                    'payed' => false,
                    'apartment' => [
                        'id' => $this->apartment->id,
                        'name' => $this->apartment->name,
                        'price' => $this->apartment->price,
                        'occupied' => true,
                        'tenant_id' => $this->tenant->id,
                    ],
                    'tenant' => [
                        'id' => $this->tenant->id,
                        'name' => $this->tenant->name,
                        'email' => $this->tenant->email,
                        'phone' => $this->tenant->phone,
                        'role' => 'Tenant'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function AAdminUserCantCreatePaymentsWithoutRequiredFields()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user, 'api');
        $data = [
            'apartment_id' => '',
            'tenant_id' => '',
            'installments' => '',
            'start_date' => '',
        ];

        $request = $this->post('/api/payments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'apartment_id' => [
                        'The apartment id field is required.'
                    ],
                    'tenant_id' => [
                        'The tenant id field is required.'
                    ],
                    'installments' => [
                        'The installments field is required.'
                    ],
                    'start_date' => [
                        'The start date field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function AAdminUserCantCreatePaymentsWithWrongNumericFields()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user, 'api');
        $data = [
            'apartment_id' => 'apartment_id',
            'tenant_id' => 'tenant_id',
            'installments' => 'installments',
            'start_date' => Carbon::now()->toDateString(),
        ];

        $request = $this->post('/api/payments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'apartment_id' => [
                        'The apartment id must be a number.'
                    ],
                    'tenant_id' => [
                        'The tenant id must be a number.'
                    ],
                    'installments' => [
                        'The installments must be a number.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function AAdminUserCantCreatePaymentsWithWrongIdFields()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user, 'api');
        $data = [
            'apartment_id' => '2',
            'tenant_id' => '3',
            'installments' => '1',
            'start_date' => Carbon::now()->toDateString(),
        ];

        $request = $this->post('/api/payments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'apartment_id' => [
                        'The selected apartment id is invalid.'
                    ],
                    'tenant_id' => [
                        'The selected tenant id is invalid.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function AAdminUserCantCreatePaymentsWithInvalidInstallments()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user, 'api');
        $data = [
            'apartment_id' => '1',
            'tenant_id' => '2',
            'installments' => '0',
            'start_date' => Carbon::now()->toDateString(),
        ];

        $request = $this->post('/api/payments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'installments' => [
                        'The installments must be at least 1.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function AAdminUserCantCreatePaymentsWithInvalidDate()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user, 'api');
        $data = [
            'apartment_id' => '1',
            'tenant_id' => '2',
            'installments' => '1',
            'start_date' => 'date',
        ];

        $request = $this->post('/api/payments', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'start_date' => [
                        'The start date is not a valid date.'
                    ],
                ]
            ]);
    }
}
