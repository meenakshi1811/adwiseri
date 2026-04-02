<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internal_Invoices extends Model
{
    use HasFactory;
    protected $table = "internal_invoices";
    protected $primaryKey = "id";
    protected $fillable = [
        'invoice_no',
        'name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'pincode',
        'to_name',
        'to_email',
        'to_phone',
        'to_country',
        'to_state',
        'to_city',
        'to_pincode',
        'status',
        'amount',
        'discount',
        'tax',
        'total',
        'due_date',
        'type',
        'uploaded_invoice',
        'vendor_id',
    ];
    public function getFormattedDueDateAttribute()
    {
        // Get the user's country code (you can modify how you fetch the country code)
        $countryCode = (auth()->user()->country == 'United States') ? 'US' : '';


        // Define date formats based on the country
        $dateFormat = match (strtoupper($countryCode)) {
            'US' => 'd-m-Y', // MM/DD/YYYY for US
            default => 'd-m-Y', // DD-MM-YYYY for other countries
        };

        // Format and return the `dob` field
        return $this->due_date ? Carbon::parse($this->due_date)->format($dateFormat) : null;
    }
    
}
