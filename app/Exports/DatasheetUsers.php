<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Clients;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatasheetUsers implements FromCollection, WithTitle, WithHeadings
{
    private $data;

    public function __construct(int $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $users = User::where('added_by','=',$this->data)->get();
        $i = 1; $users_data_array = array();
        foreach($users as $data){

            $users_data_array[] = array('Sr No.' => $i,
                'ID' => $data->id,
                'Added_by' => $data->added_by,
                'Name' => $data->name,
                'Email' => $data->email,
                'DOB' => $data->dob,
                'Phone' => $data->phone,
                'Status' => $data->status,
                'Referral' => $data->referral,
                'Organization' => $data->organization,
                'Logo' => $data->organization_logo,
                'Designation' => $data->designation,
                'Address' => $data->address_line,
                'Country' => $data->country,
                'State' => $data->state,
                'City' => $data->city,
                'Postcode' => $data->pincode,
                'Timezone' => $data->timezone,
                'Currency' => $data->currency,
                'Category' => $data->category,
                'Sub_Category' => $data->sub_category,
                'Other_Subcategory' => $data->other_subcategory,
                'Profile Image' => $data->profile_img,
                'Created_at' => date("d-m-Y H:i:s", strtotime($data->created_at)),
                'Updated_at' => date("d-m-Y H:i:s", strtotime($data->updated_at)),);
            $i++;
        }
        return collect($users_data_array);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Users(' . $this->data . ')';
    }

    public function headings(): array
    {
        return ['Sr No.', 'UserID', 'Added_by', 'Name', 'Email', 'DOB', 'Phone', 'Status', 'Referral', 'Organization', 'Logo', 'Designation', 'Address', 'Country', 'State', 'City', 'Postcode', 'Timezone', 'Currency', 'Category', 'Sub_Category', 'Other_Subcategory', 'Profile Image', 'Created_at', 'Updated_at'];
    }
}
