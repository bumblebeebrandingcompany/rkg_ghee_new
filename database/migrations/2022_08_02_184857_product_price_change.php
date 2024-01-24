<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class ProductPriceChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        // tin product
        Product::where('barcodes', 'CT0001')->update([
            'price_per_bundle' => 4320.00
        ]);


        Product::where('barcodes', 'CT0002')->update([
            'price_per_bundle' => 5200.00
        ]);

        Product::where('barcodes', 'CT0003')->update([
            'price_per_bundle' => 9390.00
        ]);

        Product::where('barcodes', 'CT0004')->update([
            'price_per_bundle' => 9120.00
        ]);

        Product::where('barcodes', 'CT0005')->update([
            'price_per_bundle' => 9472.00
        ]);

        Product::where('barcodes', 'CT0006')->update([
            'price_per_bundle' => 9336.00
        ]);

        Product::where('barcodes', 'CT0007')->update([
            'price_per_bundle' => 8628.00
        ]);

        Product::where('barcodes', 'CT0008')->update([
            'price_per_bundle' => 5670.00

        ]);

        Product::where('barcodes', 'CT0009')->update([
            'price_per_bundle' => 8370.00
        ]);
        Product::where('barcodes', 'CT0010')->update([
            'price_per_bundle' => 9185.00
        ]);

        // jar product

        Product::where('barcodes', 'CJ0001')->update([
            'price_per_bundle' => 8280.00
        ]);
        Product::where('barcodes', 'CJ0002')->update([
            'price_per_bundle' => 7560.00
        ]);
        Product::where('barcodes', 'CJ0003')->update([
            'is_disable' => 1
        ]);
        Product::where('barcodes', 'CJ0004')->update([
            'is_disable' => 1
        ]);
        Product::where('barcodes', 'CJ0005')->update([
            'price_per_bundle' => 6200.00
        ]);
        Product::where('barcodes', 'CJ0006')->update([
            'price_per_bundle' => 9000.00
        ]);
        Product::where('barcodes', 'CJ0007')->update([
            'price_per_bundle' => 7044.00
        ]);
        Product::where('barcodes', 'CJ0008')->update([
            'price_per_bundle' => 6972.00
        ]);
        Product::where('barcodes', 'CJ0009')->update([
            'price_per_bundle' => 8616.00
        ]);
        Product::where('barcodes', 'CJ0010')->update([
            'price_per_bundle' => 5670.00
        ]);

        // pouch product

        Product::where('barcodes', 'CP0001')->update([
            'price_per_bundle' => 7680.00,
        ]);
        Product::where('barcodes', 'CP0002')->update([
            'price_per_bundle' => 7320.00,
        ]);
        Product::where('barcodes', 'CP0002')->update([
            'price_per_bundle' => 7152.00,
        ]);
        Product::where('barcodes', 'CP0004')->update([
            'price_per_bundle' => 8850.00,
        ]);
        Product::where('barcodes', 'CP0005')->update([
            'price_per_bundle' => 6948.00,
        ]);



        // saram product

        Product::where('barcodes', 'CS0001')->update([
            'price_per_bundle' => 3300.00
        ]);
        Product::where('barcodes', 'CS0002')->update([
            'price_per_bundle' => 3150.00
        ]);

        // liquid ghee

        Product::where('barcodes', 'LT0001')->update([
            'is_disable' => 1
        ]);
        Product::where('barcodes', 'LT0002')->update([
            'price_per_bundle' => 8460.00
        ]);
        Product::where('barcodes', 'LT0003')->update([
            'price_per_bundle' => 9275.00
        ]);

        // Bufflow ghee

        Product::where('barcodes', 'BT0002')->update([
            'price_per_bundle' => 9185.00
        ]);
        Product::where('barcodes', 'BT0001')->update([
            'price_per_bundle' => 8370.00
        ]);

        // bufflow jar

        Product::where('barcodes', 'BJ0001')->update([
            'price_per_bundle' => 8280.00

        ]);
        Product::where('barcodes', 'BJ0002')->update([
            'price_per_bundle' => 7560.00
        ]);
        Product::where('barcodes', 'BJ0003')->update([
            'price_per_bundle' => 6200.00
        ]);
        Product::where('barcodes', 'BJ0004')->update([
            'price_per_bundle' => 9000.00
        ]);
        Product::where('barcodes', 'BJ0005')->update([
            'price_per_bundle' => 7044.00
        ]);

        // Roeasted cow ghee

        Product::where('barcodes', 'RJ0001')->update([
            'price_per_bundle' => 6200.00

        ]);
        Product::where('barcodes', 'RJ0002')->update([
            'price_per_bundle' => 9000.00
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
