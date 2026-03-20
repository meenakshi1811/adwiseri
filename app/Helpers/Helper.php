<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// if (!function_exists('formatDOBDate')) {
//     function formatDOBDate($date, $user) {
//         Log::debug('formatDOBDate called');

//         $countryCode = ($user->country === 'United States') ? 'US' : $user->country;
//         Log::debug('Country Code: ' . $countryCode);

//         $dateFormat = match (strtoupper($countryCode)) {
//             'US' => 'd-m-Y',
//             default => 'd-m-Y',
//         };

//         Log::debug('Date Format: ' . $dateFormat);

//         return $date ? Carbon::parse($date)->format($dateFormat) : null;
//     }
// }
if (!function_exists('commission_amount')) {
    function commission_amount($commission,$amount,$find_referral) {
        if ($find_referral->user_type === 'Subscriber') {
            // Always apply 15% commission for Subscribers
            $total = $amount * 0.15;
        } else {
            // Apply tiered commission for other user types
            if ($commission <= 10000) {
                $total = $amount * 0.15; // 15% commission
            } elseif ($commission > 10000 && $commission <= 25000) {
                $total = $amount * 0.18; // 18% commission
            } else {
                $total = $amount * 0.20; // 20% commission
            }
        }
        return round($total,2);
    }
}
