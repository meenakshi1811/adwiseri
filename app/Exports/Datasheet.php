<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Clients;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Datasheet implements FromQuery, WithTitle, WithHeadings
{
    private $data;

    public function __construct(int $data)
    {
        $this->data = $data;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Clients
            ::query()
            ->where('subscriber_id', $this->data);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Clients ' . $this->data;
    }

    public function headings(): array
    {
        return ["client_id", "subscriber_id", "user_id", "name", "phone", "email", "alternate_no", "passport_no", "dob", "address", "nationality", "country", "state", "city", "pincode", "profile_img"];
    }
}
