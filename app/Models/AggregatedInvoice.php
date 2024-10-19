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
            $currentMonth = Carbon::now()->format('m');

            // Check the last entry
            $lastEntry = static::whereYear('created_at', $currentYear)
                ->orderBy('yearly_counter', 'desc')
                ->first();

            if ($lastEntry) {
                $counter = $lastEntry->yearly_counter + 1;
            } else {
                $counter = 1; // Reset counter at the beginning of each year
            }

            // Assign the generated ID to the model
            $model->yearly_counter = $counter;
            $model->number = $currentYear  . $currentMonth  . $counter;
        });
    }
}
