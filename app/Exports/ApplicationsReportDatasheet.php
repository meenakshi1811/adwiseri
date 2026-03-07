<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Clients;
use App\Models\Applications;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApplicationsReportDatasheet implements FromCollection, WithTitle, WithHeadings
{
    private $data;

    public function __construct(int $data, $key)
    {
        $this->data = $data;
        $this->key = $key;
    }
    
    public function collection()
    {
        $apps = Applications::where('subscriber_id','=',$this->data)->where('application_name', $this->key)->get();
        $i = 1; $apps_data_array = array();
        foreach($apps as $data){

            $apps_data_array[] = array('Sr No.' => $i,
                'ID' => $data->id,
                'Client_ID' => $data->client_id,
                'Subscriber_ID' => $data->subscriber_id,
                'Application NO.' => $data->application_id,
                // 'Category' => $data->application_category,
                // 'Subcategory' => $data->application_subcategory,
                'Application Type' => $data->application_name,
                'Visa Country' => $data->application_country,
                'Remarks' => $data->application_detail,
                'Start Date' => $data->start_date,
                'End Date' => $data->end_date,
                'Status' => $data->application_status,
                'Created_at' => date("d-m-Y H:i:s", strtotime($data->created_at)),
                'Updated_at' => date("d-m-Y H:i:s", strtotime($data->updated_at)),);
            $i++;
        }
        return collect($apps_data_array);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->key;
    }

    public function headings(): array
    {
        return ['Sr No.', 'ApplicationID', 'Client_ID', 'Subscriber_ID', 'Application NO.', 'Application Type', 'Visa Country', 'Remarks', 'Start Date', 'End Date', 'Status', 'Created_at', 'Updated_at'];
    }
}
