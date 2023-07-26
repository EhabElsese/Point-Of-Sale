<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = ['مرتبه', 'مخده','فايبر'];

        foreach ($products as $index => $product) {

            \App\Product::create([
                'category_id' => $index + 1,
                'ar' => ['name' => $product, 'description' => $product . ' desc'],
                'en' => ['name' => $product, 'description' => $product . ' desc'],
                'purchase_price' => 1000,
                'sale_price' => 1500,
                'stock' => 100,
            ]);

        }//end of foreach

    }//end of run

}//end of seeder
