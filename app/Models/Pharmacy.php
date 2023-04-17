<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pharmacy extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'phone',
        'country',
        'state',
        'city',
        'address',
        'email',
        'password',
        'is_active',
    ];
    public function statePharmacy()
    {
        return $this->belongsTo('App\Models\State', 'state', 'id');
    }

    public function countryPharmacy()
    {
        return $this->belongsTo('App\Models\Country', 'country', 'id');
    }
    public function cityPharmacy()
    {
        return $this->belongsTo('App\Models\City', 'city', 'id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'pharmacy_products')->withPivot('id', 'stock');
    }
    // relationship between accountdetail and pharmacy
    public function bankDetail()
    {
        return $this->hasOne(BankDetail::class, 'pharmacy_id');
    }

    public function withDrawalRequest()
    {
        return $this->hasMany(WithDrawalRequest::class, 'pharmacy_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'pharmacy_id');
    }
    public function pharmacyProduct()
    {
        return $this->hasMany(PharmacyProduct::class, 'pharmacy_id');
    }

    public function scopeNearestTo($query, $latitude, $longitude, $distance = 100, $unit = 'km', $limit = 5)
    {
        $haversine = sprintf(
            "(6371 * acos(cos(radians(%s)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%s)) + sin(radians(%s)) * sin(radians(latitude))))",
            $latitude,
            $longitude,
            $latitude
        );
        return $query->selectRaw("*")
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$distance])
            ->orderBy('distance', 'ASC')
            ->take($limit); // limit the query to the first 5 results
    }
}
