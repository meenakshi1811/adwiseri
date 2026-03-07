<?php

namespace App\Exports;

use Auth;
use App\Models\User;
use App\Models\Clients;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportClients implements WithMultipleSheets
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
        $subscribers = User::where('user_type','=','Subscriber')->get();
        foreach($subscribers as $subs){
            $sheets[] = new DatasheetClients($subs->id);
        }

        // foreach ($users as $u) {
        //     if($u->user_type == "Subscriber"){
        //         $sheets[] = new Datasheet($u->id);
        //     }
            
        // }

        return $sheets;
    }
}
