<?php

namespace App\Exports;

use Auth;
use App\Models\User;
use App\Models\Clients;
use App\Models\Client_jobs;
use App\Models\Applications;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportApplicationsReport implements WithMultipleSheets
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
        $client_jobs = Client_jobs::get();
        
        $applications = Applications::get();
        
        foreach($client_jobs as $job){
            $categ = $job->job;
            $categ_app = 0;
            foreach($applications as $app){
                if($categ == $app->application_name){
                    $categ_app += 1;
                }
            }
            $total_apps[$categ] = $categ_app;
        }
        

        foreach ($total_apps as $key => $app) {
            $sheets[] = new DatasheetApplicationsReport(1, $key);
        }

        return $sheets;
    }
}
