<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'section_id',
        'product_name',
        'phone',
        'address',
        'email',
    ];
    public function sections()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'product_id');
    }

}
