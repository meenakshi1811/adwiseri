<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Clients;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatasheetClients implements FromCollection, WithTitle, WithHeadings
{
    private $data;

    public function __construct(int $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $clients = Clients::where('subscriber_id','=',$this->data)->get();
        $i = 1; $clients_data_array = array();
        foreach($clients as $data){

            $clients_data_array[] = array('Sr No.' => $i,
                'ID' => $data->id,
                'Subscriber_ID' => $data->subscriber_id,
                'User_ID' => $data->user_id,
                'Name' => $data->name,
                'Phone' => $data->phone,
                'Email' => $data->email,
                'Alternate NO.' => $data->alternate_no,
                'Passport No.' => $data->passport_no,
                'DOB' => $data->dob,
                'Nationality' => $data->nationality,
                'Address' => $data->address,
                'Country' => $data->country,
                'State' => $data->state,
                'City' => $data->city,
                'Postcode' => $data->pincode,
                'Profile Image' => $data->profile_img,
                'Created_at' => date("d-m-Y H:i:s", strtotime($data->created_at)),
                'Updated_at' => date("d-m-Y H:i:s", strtotime($data->updated_at)),);
            $i++;
        }
        return collect($clients_data_array);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Clients(' . $this->data . ')';
    }

    public function headings(): array
    {
        return ['Sr No.', 'ClientID', 'Subscriber_ID', 'User_ID', 'Name', 'Phone', 'Email', 'Alternate NO.', 'Passport No.', 'DOB', 'Nationality', 'Address', 'Country', 'State', 'City', 'Postcode', 'Profile Image', 'Created_at', 'Updated_at', ];
    }
}
