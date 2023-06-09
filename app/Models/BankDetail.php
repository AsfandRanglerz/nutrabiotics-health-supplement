<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;
    protected $fillable = ['name','accountHolder','accountNumber','pharmacy_id'];
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    
}
