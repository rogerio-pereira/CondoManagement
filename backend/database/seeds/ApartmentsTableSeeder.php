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
        //Amin
        for($i=0; $i<10; $i++)
            factory(Apartment::class)->create(['name' => $i]);
    }
}
