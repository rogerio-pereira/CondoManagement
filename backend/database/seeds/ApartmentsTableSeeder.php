<?php

use Carbon\Carbon;
use App\Model\Payment;
use App\Model\Apartment;
use Illuminate\Database\Seeder;
use App\Model\Useful\DateConversion;

class ApartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<=10; $i++) {
            if($i<=5) {
                $data = [
                    'name' => $i,
                    'occupied' => true,
                    'tenant_id' => $i+9 //There is other 9 users
                ];
            }
            else {
                $data = [
                    'name' => $i,
                    'occupied' => false,
                ];
            }

            $apartment = factory(Apartment::class)->create($data);

            //Create 3 payments for each tenant
            if($i<=5) {
                $date = Carbon::now()->addMonth()->startOfMonth()->toDateString();

                for($j=0; $j<3; $j++) {
                    factory(Payment::class)->create([
                        'tenant_id' => $apartment->tenant_id,
                        'apartment_id' => $apartment->id,
                        'value' => $apartment->price,
                        'due_at' => $date,
                        'payed' => rand(0,1),
                    ]);

                    $date = DateConversion::newDateByPeriod($date, 'Monthly')->toDateString();
                }
            }
        }
    }
}
