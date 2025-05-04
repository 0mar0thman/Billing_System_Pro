<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSittings extends Model
{
    use HasFactory;
    protected $table = 'invoices_sittings';

    protected $fillable = [
        'Discount_Commission',
        'Amount_Commission',
        'Amount_collection'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
