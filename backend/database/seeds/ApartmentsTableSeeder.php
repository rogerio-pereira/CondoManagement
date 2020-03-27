<?php

use App\Model\Apartment;
use Illuminate\Database\Seeder;

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
                    'tenant_id' => $i
                ];
            }
            else {
                $data = [
                    'name' => $i,
                    'occupied' => false,
                ];
            }

            factory(Apartment::class)->create($data);
        }
    }
}
