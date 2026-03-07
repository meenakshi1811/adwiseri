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

class ExportInvoicesReport implements WithMultipleSheets
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
        $invoice_status = array("Paid", "UnPaid", "Partially_Paid", "Refunded");
        foreach ($invoice_status as $key) {
            $sheets[] = new DatasheetInvoicesReport(1, $key);
        }

        return $sheets;
    }
}
