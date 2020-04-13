<?php

declare(strict_types=1);

use App\Models\V1\Product;
use Illuminate\Database\Seeder;

/**
 * Class ProductsTableSeeder
 */
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::firstOrCreate(['ean' => 28877362779], [
            'ean'         => 28877362779,
            'description' => 'Esmerilhadeira Angular 4.1/2 220V 820W Black Decker G720',
            'price'       => 194.66,
        ]);

        Product::firstOrCreate(['ean' => 28877495873], [
            'ean'         => 28877495873,
            'description' => 'Furadeira Parafusadeira Black Decker CD961 Bateria Bivolt',
            'price'       => 229.90,
        ]);

        Product::firstOrCreate(['ean' => 885911251976], [
            'ean'         => 885911251976,
            'description' => 'Parafusadeira Bivolt 3,6V 9036 Black & Decker',
            'price'       => 112.41,
        ]);

        Product::firstOrCreate(['ean' => 885911230995], [
            'ean'         => 885911230995,
            'description' => 'Serra Tico-Tico 500W - DW300 – DeWalt',
            'price'       => 529.90,
        ]);
    }
}
