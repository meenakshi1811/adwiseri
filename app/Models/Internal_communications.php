<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internal_communications extends Model
{
    use HasFactory;
    protected $table = "internal_communications";
    protected $primaryKey = "id";
    protected $fillable = [
        'subscriber_id',
        'user_id',
        'user_name',
        'message',
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
}
