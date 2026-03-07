<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Clients;
use App\Models\Internal_Invoices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatasheetInvoices implements FromCollection, WithTitle, WithHeadings
{
    private $data;

    public function __construct(int $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $invoices = Internal_Invoices::where('subscriber_id', $this->data)->get();
        $i = 1; $invoices_data_array = array();
        foreach($invoices as $data){

            $invoices_data_array[] = array('Sr No.' => $i,
                'ID' => $data->id,
                'Invoice No.' => $data->invoice_no,
                'Subscriber_ID' => $data->subscriber_id,
                'User_ID' => $data->user_id,
                'Name' => $data->name,
                'Email' => $data->email,
                'Phone' => $data->phone,
                'Country' => $data->country,
                'State' => $data->state,
                'City' => $data->city,
                'Postcode' => $data->pincode,
                'Payer Name' => $data->to_name,
                'Payer Email' => $data->to_email,
                'Payer Phone' => $data->to_phone,
                'Payer Country' => $data->to_country,
                'Payer State' => $data->to_state,
                'Payer City' => $data->to_city,
                'Payer Postcode' => $data->to_pincode,
                'Detail' => $data->detail,
                'Due Date' => $data->due_date,
                'Status' => $data->status,
                'Amount' => $data->amount,
                'Discount' => $data->discount,
                'Tax' => $data->tax,
                'Total' => $data->total,
                'Created_at' => date("d-m-Y H:i:s", strtotime($data->created_at)),
                'Updated_at' => date("d-m-Y H:i:s", strtotime($data->updated_at)),);
            $i++;
        }
        return collect($invoices_data_array);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Invoices(' . $this->data . ')';
    }

    public function headings(): array
    {
        return ['Sr No.', 'InvoiceID', 'Invoice No.', 'Subscriber_ID', 'User_ID', 'Name', 'Email', 'Phone', 'Country', 'State', 'City', 'Postcode', 'Payer Name', 'Payer Email', 'Payer Phone', 'Payer Country', 'Payer State', 'Payer City', 'Payer Postcode', 'Detail', 'Due Date', 'Status', 'Amount', 'Discount', 'Tax', 'Total', 'Created_at', 'Updated_at'];
    }
}
