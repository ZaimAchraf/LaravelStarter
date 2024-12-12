<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'total',
        'invoice_date',
        'payments',
        'folder_id',
        'title',
        'yearly_counter'
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function invoiceLines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $currentYear = Carbon::now()->year;

            // Find the last entry in AggregatedInvoice
            $lastAggregatedEntry = static::whereYear('created_at', $currentYear)
                ->orderBy('yearly_counter', 'desc')
                ->first();

            // Find the last entry in Invoice model
            $lastInvoiceEntry = AggregatedInvoice::whereYear('created_at', $currentYear)
                ->orderBy('yearly_counter', 'desc')
                ->first();

            // Determine the maximum yearly_counter between the two entries
            $lastCounter = max(
                $lastAggregatedEntry ? $lastAggregatedEntry->yearly_counter : 0,
                $lastInvoiceEntry ? $lastInvoiceEntry->yearly_counter : 0
            );

            // Increment the counter
            $counter = $lastCounter + 1;

            // Assign the generated ID to the model
            $model->yearly_counter = $counter;
            $model->number = $currentYear . '/' . $counter;});
    }

}
