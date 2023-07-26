<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = ['مصطفى السيسي','حسن الهواري', 'حميد الدين بلابل'];

        foreach ($clients as $client) {

            \App\Client::create([
               'name' => $client,
               'phone' => '010123456789',
               'address' => 'القليوبيه - مركز الخانكه - سندوه',
            ]);

        }//end of foreach

    }//end of run

}//end of seeder
