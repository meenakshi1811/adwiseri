<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;
    protected $table = "invoices";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'company_name',
        'phone',
        'city',
        'state',
        'country',
        'pincode',
        'invoice',
        'to_name',
        'to_company',
        'to_city',
        'to_state',
        'to_country',
        'to_pincode',
        'to_phone',
        'to_email',
        'service_fee',
        'discount',
        'tax',
        'total',
        'type',
        'payment_mode'

    ];

    public function getFormattedCreatedAtAttribute()
    {
        // Get the user's country code (you can modify how you fetch the country code)
        $countryCode = (auth()->user()->country == 'United States') ? 'US' : '';


        // Define date formats based on the country
        $dateFormat = match (strtoupper($countryCode)) {
            'US' => 'd-m-Y', // MM/DD/YYYY for US
            default => 'd-m-Y', // DD-MM-YYYY for other countries
        };

        // Format and return the `dob` field
        return $this->created_at ? Carbon::parse($this->created_at)->format($dateFormat) : null;
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

     // An invoice can belong to multiple clients
     public function clients()
     {
         return $this->hasMany(Clients::class, 'user_id', 'user_id');
     }
 
     // An invoice can belong to multiple services
     public function services()
     {
         return $this->hasMany(Services::class, 'user_id', 'user_id');
     }

}
