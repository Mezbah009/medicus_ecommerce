<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    public $fillable =['user_id','product_id','product_item_id'];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    // Wishlist model
    public function product_items()
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }

}
