<?php

namespace App\Exports;

use Auth;
use App\Models\User;
use App\Models\Clients;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersExport implements WithMultipleSheets
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
        $sheets[] = new UsersDatasheet($user->id);

        // foreach ($users as $u) {
        //     if($u->user_type == "Subscriber"){
        //         $sheets[] = new Datasheet($u->id);
        //     }
            
        // }

        return $sheets;
    }
}
