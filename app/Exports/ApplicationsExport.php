<?php

namespace App\Exports;

use Auth;
use App\Models\User;
use App\Models\Clients;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ApplicationsExport implements WithMultipleSheets
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
        $sheets[] = new ApplicationsDatasheet($subscriber->id);

        // foreach ($users as $u) {
        //     if($u->user_type == "Subscriber"){
        //         $sheets[] = new Datasheet($u->id);
        //     }
            
        // }

        return $sheets;
    }
}
