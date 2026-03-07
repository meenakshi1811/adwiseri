<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Clients;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatasheetSubscriber implements FromCollection, WithTitle, WithHeadings
{
    private $data;

    public function __construct(int $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $users = User::where('user_type','=',"Subscriber")->get();
        $i = 1; $users_data_array = array();
        foreach($users as $data){

            $users_data_array[] = array('Sr No.' => $i,
                'ID' => $data->id,
                'Name' => $data->name,
                'Email' => $data->email,
                // 'DOB' => $data->dob,
                'Phone' => $data->phone,
                'Status' => $data->status,
                'Subscription' => $data->membership,
                'Subscription Start Date' => $data->membership_start_date,
                'Subscription End Date' => $data->membership_expiry_date,
                'Wallet' => $data->wallet,
                'Referral' => $data->referral,
                'Organization' => $data->organization,
                'Logo' => $data->organization_logo,
                'Designation' => $data->designation,
                'Employee Strength' => $data->employee_strength,
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
        return 'Users ' . $this->data;
    }

    public function headings(): array
    {
        return ['Sr No.', 'SubscriberID', 'Name', 'Email', 'Phone', 'Status', 'Subscription', 'Subscription Start Date', 'Subscription End Date', 'Wallet', 'Referral', 'Organization', 'Logo', 'Designation', 'Employee Strength', 'Address', 'Country', 'State', 'City', 'Postcode', 'Timezone', 'Currency', 'Category', 'Sub_Category', 'Other_Subcategory', 'Profile Image', 'Created_at', 'Updated_at'];
    }
}
