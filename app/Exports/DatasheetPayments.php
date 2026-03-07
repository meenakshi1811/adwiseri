<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Clients;
use App\Models\Invoices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatasheetPayments implements FromCollection, WithTitle, WithHeadings
{
    private $data;

    public function __construct(int $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $invoices = Invoices::where('user_id', $this->data)->get();
        $i = 1; $invoices_data_array = array();
        foreach($invoices as $data){

            $invoices_data_array[] = array('Sr No.' => $i,
                'ID' => $data->id,
                'Payment No.' => $data->invoice_no,
                'Company Name' => $data->user_id,
                'Name' => $data->name,
                'Email' => $data->email,
                'Phone' => $data->phone,
                'Country' => $data->country,
                'State' => $data->state,
                'City' => $data->city,
                'Postcode' => $data->pincode,
                'Payer Name' => $data->to_name,
                'Payer Company' => $data->to_company,
                'Payer Email' => $data->to_email,
                'Payer Phone' => $data->to_phone,
                'Payer Country' => $data->to_country,
                'Payer State' => $data->to_state,
                'Payer City' => $data->to_city,
                'Payer Postcode' => $data->to_pincode,
                'Service' => $data->service_fee,
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
        return 'Payments(' . $this->data . ')';
    }

    public function headings(): array
    {
        return [
            'Sr No.', 'ID', 'Payment No.', 'Company Name', 'Name', 'Email', 'Phone', 'Country', 'State', 'City', 'Postcode', 'Payer Name', 'Payer Company', 'Payer Email', 'Payer Phone', 'Payer Country', 'Payer State', 'Payer City', 'Payer Postcode', 'Service', 'Discount', 'Tax', 'Total', 'Created_at', 'Updated_at'];
    }
}
