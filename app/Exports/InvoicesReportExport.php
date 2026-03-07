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

class InvoicesReportExport implements WithMultipleSheets
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
        $user = Auth::user();
        if($user->user_type == "Subscriber"){
            $subscriber = $user;
        }
        else{
            $subscriber = User::find($user->added_by);
        }

        $invoice_status = array("Paid", "UnPaid", "Partially_Paid", "Refunded");
        

        foreach ($invoice_status as $key) {
            $sheets[] = new InvoicesReportDatasheet($subscriber->id, $key);
        }

        return $sheets;
    }
}
