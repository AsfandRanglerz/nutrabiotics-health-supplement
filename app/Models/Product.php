<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable=['product_name','subcategory_id','category_id','price','stock','description','status'];
    public function pharmacies()
    {
        return $this->belongsToMany(Pharmacy::class, 'pharmacy_products');
    }
//     public function pharmacies()
// {
//     return $this->belongsToMany(Pharmacy::class)->withPivot('price', 'stock', 'description');
// }
    public function subcategory()
    {
        return $this->belongsTo('App\Models\SubCategory', 'subcategory_id', 'id');
    }
    public function pharmacyproduct(){
        return $this->hasMany(PharmacyProduct::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'product_id');
    }
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class,'product_id','id');
    }
    public function pharmacy()
    {
        return $this->belongsToMany(Pharmacy::class, 'pharmacy_products')->withPivot('id','stock');
    }


}
