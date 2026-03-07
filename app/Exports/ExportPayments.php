<?php

namespace App\Exports;

use Auth;
use App\Models\User;
use App\Models\Clients;
use App\Models\Client_jobs;
use App\Models\Applications;
use App\Models\Internal_Invoices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportPayments implements WithMultipleSheets
{

    // protected $data;
    
    // public function __construct(int $data)
    // {
    //     $this->data = $data;
    // }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $subscribers = User::where('user_type','=','Subscriber')->get();
        foreach ($subscribers as $subscriber) {
            $sheets[] = new DatasheetPayments($subscriber->id);
        }

        return $sheets;
    }
}
