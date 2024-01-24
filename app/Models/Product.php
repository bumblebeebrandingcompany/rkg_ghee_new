<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // sku series 
    public static function sku_list(){
        return Product::selectRaw("substring(barcodes, 1, 2) as barcode")->groupBy('barcode')->pluck('barcode')->toArray();
    }

}
