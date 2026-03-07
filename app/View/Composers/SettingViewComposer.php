<?php

namespace App\View\Composers;

use App\Models\Contactus;
use App\Models\Tickets;
use Illuminate\View\View;

class SettingViewComposer
{
    public function compose(View $view)
    {

         // Retrieve the data
        $contact = Contactus::first();
        $totalTickets = Tickets::where('status', 'Open')->count();
        $envelopeCount = 0; // Replace with actual logic for message count, e.g. unread messages

        // Share the data with the view
        $view->with([
            'contact' => $contact,
            'totalTickets' => $totalTickets,
            'envelopeCount' => $envelopeCount,
        ]);
    }
}
