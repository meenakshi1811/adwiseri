<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    use HasFactory;
    protected $table = "applications";
    protected $primaryKey = "id";
    protected $fillable = [
        'client_id',
        'client_name',
        'application_id',
        'application_name',
        'application_program',
        'application_country',
        'application_detail',
        'start_date',
        'end_date',
        'application_status',
        'subscriber_id',
        'visa_country'
    ];
    public function client(){
        return $this->belongsTo(Clients::class,'client_id');
    }
    public function subscriber(){
        return $this->belongsTo(User::class,'subscriber_id');
    }
    public function docs(){
        return $this->hasMany(Client_Docs::class,'application_id','application_id');
    }


    public function getFormattedStartDateAttribute()
    {
        // Get the user's country code (you can modify how you fetch the country code)
        $countryCode = (auth()->user()->country == 'United States') ? 'US' : '';


        // Define date formats based on the country
        $dateFormat = match (strtoupper($countryCode)) {
            'US' => 'd-m-Y', // MM/DD/YYYY for US
            default => 'd-m-Y', // DD-MM-YYYY for other countries
        };

        // Format and return the `dob` field
        return $this->start_date ? Carbon::parse($this->start_date)->format($dateFormat) : null;
    }
    public function getFormattedEndDateAttribute()
    {
        // Get the user's country code (you can modify how you fetch the country code)
        $countryCode = (auth()->user()->country == 'United States') ? 'US' : '';


        // Define date formats based on the country
        $dateFormat = match (strtoupper($countryCode)) {
            'US' => 'd-m-Y', // MM/DD/YYYY for US
            default => 'd-m-Y', // DD-MM-YYYY for other countries
        };

        // Format and return the `dob` field
        return $this->end_date ? Carbon::parse($this->end_date)->format($dateFormat) : null;
    }
}
