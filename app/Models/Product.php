<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id')->select('product_images.id', 'product_images.product_id', DB::raw("CONCAT('" . url('public/uploads/products/') . "/', product_images.image) AS image_url"));
    }
    
    public function shoplocation()
    {
        return $this->hasOne(AdminLocation::class, 'id', 'shop_location_id');
    }
}
