<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;
    protected $table = 'invoices_details' ;
    protected $fillable = [
        'product_name',
        'section_name',
        'invoice_id',
        'product_id',
        'section_id',

        'email',
        'address',
        'phone',

        'Status',
        'Value_Status',
        'Payment_Date',
        'note',
        'user',
    ];
    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'id_Invoice');
    }

}
