<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'invoice_Date',
        'Due_date',
        'product_name',
        'section_name',

        'product_id',
        'section_id',

        'Amount_collection',
        'Amount_Commission',
        'Discount_Commission',
        'Value_VAT',
        'Rate_VAT',
        'Total',
        'Status',
        'Value_Status',
        'note',
        'Payment_Date',
    ];

    protected $dates = ['deleted_at'];
    public function sections()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attachments()
    {
        return $this->hasMany(InvoiceAttachments::class, 'invoice_id');
    }

    public function scopePaid($query)
    {
        return $query->where('Value_Status', 1);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('Value_Status', 2);
    }

    public function scopePartial($query)
    {
        return $query->where('Value_Status', 3);
    }
}
