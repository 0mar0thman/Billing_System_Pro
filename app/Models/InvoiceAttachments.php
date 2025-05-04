<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAttachments extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_name',
        'invoice_id',
        'Created_by',
        // 'product',
        // 'Section',
        // 'Status',
        // 'Value_Status',
        // 'note',
        // 'user',
        // 'Payment_Date',
    ];
    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
