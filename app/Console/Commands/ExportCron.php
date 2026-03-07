<?php

namespace App\Console\Commands;
use DB;
use Auth;
use Hash;
use App;
use Mail;
use Session;
use Cookie;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;

use App\Models\User;
use App\Models\Applications;
use App\Models\Internal_Invoices;

use App\Mail\ReportMail;

use App\Exports\ReportExportApplications;
use App\Exports\ReportExportInvoices;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Console\Command;

class ExportCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('user_type','=', 'Subscriber')->get();
        foreach($users as $user){
            $mdata = 0;
            $set_app = 0;
            $set_inv = 0;
            $my_apps = Applications::where('subscriber_id','=',$user->id)->where('created_at','>=', date("Y-07-01 00:00:00"))->where('created_at','<=',date("Y-07-t 23:59:59"))->get();
            if(count($my_apps) > 0){
                Excel::store(new ReportExportApplications($user->id), '/User'.$user->id.'/'.time().'_Application_Report.xlsx', 'exports');
                $set_app = 1;
                $mdata = 1;
            }
            $invoices = Internal_Invoices::where('subscriber_id', $user->id)->where('created_at','>=', date("Y-07-01 00:00:00"))->where('created_at','<=',date("Y-07-t 23:59:59"))->get();
            if(count($invoices) > 0){
                Excel::store(new ReportExportInvoices($user->id), '/User'.$user->id.'/'.time().'_Invoice_Report.xlsx', 'exports');
                $set_inv = 1;
                $mdata = 1;
            }
            if($mdata == 1){
                $mail_data = new \stdClass();
                $mail_data->id = $user->id;
                $mail_data->name = $user->name;
                if($set_app == 1){
                    $mail_data->application = time().'_Application_Report.xlsx';
                }
                if($set_inv == 1){
                    $mail_data->invoice = time().'_Invoice_Report.xlsx';
                }
                Mail::to($user->email)->send(new ReportMail($mail_data));
                // Mail::to("s.k.sangwalvip@gmail.com")->send(new ReportMail($mail_data));
                if(Mail::failures()){
                    echo "Fail";
                }
                else{
                    echo "Success";
                }
            }
        }
    }
}
