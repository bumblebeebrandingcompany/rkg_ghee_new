<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([

            ['pack_type' => "tin",
                'pack_size' => '15',
                'pack_size_unit' => 'kg',
                'pcs_per_bundle' => '1',
                'price_per_bundle' => 8375,
                'weight_per_bundle' => '15',
                'points_per_bundle' => '0',
                'barcodes' => 'CT0010',
                
                'product_type' => 'cow_ghee'
            ],

            ['pack_type' => "tin",
                'pack_size' => '15',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '1',
                'price_per_bundle' => 7620,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '0',
                'barcodes' => 'CT0009',
                
                'product_type' => 'cow_ghee'
            ],

            ['pack_type' => "tin",
                'pack_size' => '10',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '1',
                'price_per_bundle' => 5170,
                'weight_per_bundle' => '9.1',
                'points_per_bundle' => '0',
                'barcodes' => 'CT0008',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '5',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '3',
                'price_per_bundle' => 7878,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '0',
                'barcodes' => 'CT0007',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '2',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '8',
                'price_per_bundle' => 8536,
                'weight_per_bundle' => '14.56',
                'points_per_bundle' => '0',
                'barcodes' => 'CT0006',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '1',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '16',
                'price_per_bundle' => 8672,
                'weight_per_bundle' => '14.56',
                'points_per_bundle' => '6',
                'barcodes' => 'CT0005',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '500',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '30',
                'price_per_bundle' => 8370,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '6',
                'barcodes' => 'CT0004',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '200',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '75',
                'price_per_bundle' => 8640,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '10',
                'barcodes' => 'CT0003',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '100',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '80',
                'price_per_bundle' => 4800,
                'weight_per_bundle' => '7.2',
                'points_per_bundle' => '6',
                'barcodes' => 'CT0002',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '50',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '120',
                'price_per_bundle' => 4032,
                'weight_per_bundle' => '5.4',
                'points_per_bundle' => '6',
                'barcodes' => 'CT0001',
                
                'product_type' => 'cow_ghee'
            ],



            ['pack_type' => "jar",
                'pack_size' => '10',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '1',
                'price_per_bundle' => 5170,
                'weight_per_bundle' => '9.1',
                'points_per_bundle' => '0',
                'barcodes' => 'CJ0010',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '5',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '3',
                'price_per_bundle' => 7866,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '6',
                'barcodes' => 'CJ0009',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '2',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '6',
                'price_per_bundle' => 6360,
                'weight_per_bundle' => '10.92',
                'points_per_bundle' => '0',
                'barcodes' => 'CJ0008',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '1',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '12',
                'price_per_bundle' => 6444,
                'weight_per_bundle' => '10.92',
                'points_per_bundle' => '6',
                'barcodes' => 'CJ0007',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '500',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '30',
                'price_per_bundle' => 8250,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '6',
                'barcodes' => 'CJ0006',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '200',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '50',
                'price_per_bundle' => 5705,
                'weight_per_bundle' => '9.1',
                'points_per_bundle' => '10',
                'barcodes' => 'CJ0005',
                
                'product_type' => 'cow_ghee'
            ],
            
            ['pack_type' => "jar",
                'pack_size' => '100',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '120',
                'price_per_bundle' => 7020,
                'weight_per_bundle' => '10.8',
                'points_per_bundle' => '10',
                'barcodes' => 'CJ0002',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '50',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '240',
                'price_per_bundle' => 7728,
                'weight_per_bundle' => '10.8',
                'points_per_bundle' => '10',
                'barcodes' => 'CJ0001',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "pouch",
                'pack_size' => '1',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '12',
                'price_per_bundle' => 6348,
                'weight_per_bundle' => '10.92',
                'points_per_bundle' => '6',
                'barcodes' => 'CP0005',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "pouch",
                'pack_size' => '500',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '30',
                'price_per_bundle' => 8100,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '6',
                'barcodes' => 'CP0004',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "pouch",
                'pack_size' => '200',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '60',
                'price_per_bundle' => 6552,
                'weight_per_bundle' => '10.92',
                'points_per_bundle' => '10',
                'barcodes' => 'CP0003',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "pouch",
                'pack_size' => '100',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '120',
                'price_per_bundle' => 6780,
                'weight_per_bundle' => '10.80',
                'points_per_bundle' => '10',
                'barcodes' => 'CP0002',
                
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "pouch",
                'pack_size' => '50',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '240',
                'price_per_bundle' => 7152,
                'weight_per_bundle' => '10.80',
                'points_per_bundle' => '10',
                'barcodes' => 'CP0001',
                
                'product_type' => 'cow_ghee'
            ],


            ['pack_type' => "sachet",
                'pack_size' => '0',
                'pack_size_unit' => 'sachet',
                'pcs_per_bundle' => '500',
                'price_per_bundle' => 3150,
                'weight_per_bundle' => '5.5',
                'points_per_bundle' => '6',
                'barcodes' => 'CS0002',
                'product_type' => 'cow_ghee'
            ],
            ['pack_type' => "sachet",
                'pack_size' => '0',
                'pack_size_unit' => 'sachet',
                'pcs_per_bundle' => '1000',
                'price_per_bundle' => 3300,
                'weight_per_bundle' => '6',
                'points_per_bundle' => '0',
                'barcodes' => 'CS0001',
                'product_type' => 'cow_ghee'
            ],

            ['pack_type' => "tin",
                'pack_size' => '15',
                'pack_size_unit' => 'kg',
                'pcs_per_bundle' => '1',
                'price_per_bundle' => 8465,
                'weight_per_bundle' => '15',
                'points_per_bundle' => '0',
                'barcodes' => 'LT0003',
                
                'product_type' => 'thelivu_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '15',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '1',
                'price_per_bundle' => 7710,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '0',
                'barcodes' => 'LT0002',
                
                'product_type' => 'thelivu_ghee'
            ],
            // ['pack_type' => "tin",
            //     'pack_size' => '5',
            //     'pack_size_unit' => 'ltr',
            //     'pcs_per_bundle' => '3',
            //     'price_per_bundle' => 7878,
            //     'weight_per_bundle' => '13.65',
            //     'points_per_bundle' => '0',
            //     'barcodes' => 'LT0001',
                
            //     'product_type' => 'thelivu_ghee'
            // ],


            ['pack_type' => "tin",
                'pack_size' => '15',
                'pack_size_unit' => 'kg',
                'pcs_per_bundle' => '1',
                'price_per_bundle' => 8375,
                'weight_per_bundle' => '15',
                'points_per_bundle' => '0',
                'barcodes' => 'BT0002',
                
                'product_type' => 'buffalo_ghee'
            ],
            ['pack_type' => "tin",
                'pack_size' => '15',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '1',
                'price_per_bundle' => 7620,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '0',
                'barcodes' => 'BT0001',
                
                'product_type' => 'buffalo_ghee'
            ],

            ['pack_type' => "jar",
                'pack_size' => '1',
                'pack_size_unit' => 'ltr',
                'pcs_per_bundle' => '12',
                'price_per_bundle' => 6444,
                'weight_per_bundle' => '10.92',
                'points_per_bundle' => '6',
                'barcodes' => 'BJ0005',
                
                'product_type' => 'buffalo_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '500',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '30',
                'price_per_bundle' => 8250,
                'weight_per_bundle' => '13.65',
                'points_per_bundle' => '6',
                'barcodes' => 'BJ0004',
                
                'product_type' => 'buffalo_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '200',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '50',
                'price_per_bundle' => 5705,
                'weight_per_bundle' => '9.1',
                'points_per_bundle' => '10',
                'barcodes' => 'BJ0003',
                
                'product_type' => 'buffalo_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '100',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '120',
                'price_per_bundle' => 4680,
                'weight_per_bundle' => '10.8',
                'points_per_bundle' => '10',
                'barcodes' => 'BJ0002',
                
                'product_type' => 'buffalo_ghee'
            ],
            ['pack_type' => "jar",
                'pack_size' => '50',
                'pack_size_unit' => 'ml',
                'pcs_per_bundle' => '240',
                'price_per_bundle' => 3864,
                'weight_per_bundle' => '10.8',
                'points_per_bundle' => '10',
                'barcodes' => 'BJ0001',
                
                'product_type' => 'buffalo_ghee'
            ]
        ]
        );
    }
}
