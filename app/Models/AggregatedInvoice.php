<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AggregatedInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client',
        'number',
        'invoice_date',
        'payments',
        'total',
        'title',
        'yearly_counter'
    ];

    public function invoiceLines()
    {
        return $this->hasMany(AggregatedInvoiceLines::class, 'invoice_id');
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
            $lastInvoiceEntry = Invoice::whereYear('created_at', $currentYear)
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
            $model->number = $currentYear . '/' . $counter;        });
    }
}
