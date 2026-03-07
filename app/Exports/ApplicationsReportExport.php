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

class ApplicationsReportExport implements WithMultipleSheets
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
        
        if($subscriber->category == "Law Firm"){
            $client_jobs = Client_jobs::where('category','=',$subscriber->category)->get();
        }
        elseif($subscriber->category == "Travel Agency"){
            $client_jobs = Client_jobs::where('category','=',$subscriber->category)->get();
        }
        else{
            $client_jobs = Client_jobs::where('category','=',$subscriber->category)->where('sub_category','=',$subscriber->sub_category)->get();
        }
        
        $applications = Applications::where('subscriber_id','=',$subscriber->id)->get();
        
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
            $sheets[] = new ApplicationsReportDatasheet($subscriber->id, $key);
        }

        return $sheets;
    }
}
