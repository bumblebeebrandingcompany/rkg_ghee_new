<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class AddNewProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('products')->insert([

            ['pack_type' => "jar",
                'pack_size' => '200',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '50',
                'price_per_bundle' => 5705,
                'weight_per_bundle' => '9.1',
                'points_per_bundle' => '10',
                'barcodes' => 'RJ0001',
                
                'product_type' => 'roasted_cow_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '500',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '30',
                'price_per_bundle' => 8250,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '6',
                'barcodes' => 'RJ0002',
                
                'product_type' => 'roasted_cow_ghee'
            ]
        ]
        );
    }
}
