<?php

namespace App\Http\Controllers;
// date_default_timezone_set("Asia/Kolkata");
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
 
use App\Mail\EmailVerification;
use App\Models\User;
use App\Models\Clients;
use App\Models\Client_Docs;
use App\Models\Countries;
use App\Models\States;
use App\Models\Subscriber_Categories;
use App\Models\Subscriber_Sub_Categories;
use App\Models\Contactus;
use App\Models\Features;
use App\Models\Membership;
use App\Models\About_Advisori;
use App\Models\Invoices;
use App\Models\Internal_Invoices;
use App\Models\Job_roles;
use App\Models\Activities;
use App\Models\Client_jobs;
use App\Models\Messages;
use App\Models\Referrals;
use App\Models\Applications;
use App\Models\Tickets;
use App\Models\Faq;
use App\Models\Invoice_settings;
use App\Models\Used_referrals;
use App\Models\Application_assignments;
use App\Models\Internal_communications;
use App\Models\Client_discussions;
use App\Mail\ReportMail;
use App\Exports\UsersExport;
use App\Exports\ClientsExport;
use App\Exports\ApplicationsExport;
use App\Exports\ApplicationsReportExport;
use App\Exports\InvoicesReportExport;
use App\Exports\ReportExportApplications;
use App\Exports\ReportExportInvoices;
use App\Exports\ExportSubscriber;
use App\Exports\ExportUsers;
use App\Exports\ExportClients;
use App\Exports\ExportApplications;
use App\Exports\ExportInvoices;
use App\Exports\ExportPayments;
use App\Exports\ExportApplicationsReport;
use App\Exports\ExportInvoicesReport;

use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export_users() 
    {
        $user = Auth::user();
        if($user->user_type == "Subscriber"){
            $subscriber = $user;
        }
        else{
            $subscriber = User::find($user->added_by);
        }
        return Excel::download(new UsersExport, 'Users_Report('.$subscriber->id.').xlsx');
    }

    public function export_clients() 
    {
        $user = Auth::user();
        if($user->user_type == "Subscriber"){
            $subscriber = $user;
        }
        else{
            $subscriber = User::find($user->added_by);
        }
        return Excel::download(new ClientsExport, 'Clients_Report('.$subscriber->id.').xlsx');
    }

    public function export_applications() 
    {
        $user = Auth::user();
        if($user->user_type == "Subscriber"){
            $subscriber = $user;
        }
        else{
            $subscriber = User::find($user->added_by);
        }
        return Excel::download(new ApplicationsExport, 'Applications('.$subscriber->id.').xlsx');
    }

    public function export_applications_report() 
    {
        $user = Auth::user();
        if($user->user_type == "Subscriber"){
            $subscriber = $user;
        }
        else{
            $subscriber = User::find($user->added_by);
        }
        return Excel::download(new ApplicationsReportExport, 'Applications_Report('.$subscriber->id.').xlsx');
    }

    public function export_invoices_report() 
    {
        $user = Auth::user();
        if($user->user_type == "Subscriber"){
            $subscriber = $user;
        }
        else{
            $subscriber = User::find($user->added_by);
        }
        return Excel::download(new InvoicesReportExport, 'Invoices_Report('.$subscriber->id.').xlsx');
    }
    
    public function set_timezone()
    {
        $user = Auth::user();
        if($user){
            date_default_timezone_set($user->timezone);
        }
    }

    public function store_report(){
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
                if(Mail::failures()){
                    echo "Fail";
                }
                else{
                    echo "Success";
                }
            }
            
        }
    }

    // Admin Exports
    public function subscribers_export() 
    {
        $user = Auth::user();
        return Excel::download(new ExportSubscriber, 'Subscribers_Report.xlsx');
    }
    public function users_export() 
    {
        $user = Auth::user();
        return Excel::download(new ExportUsers, 'Users_Report.xlsx');
    }
    public function clients_export() 
    {
        $user = Auth::user();
        return Excel::download(new ExportClients, 'Clients_Report.xlsx');
    }
    public function payments_export()
    {
        $user = Auth::user();
        return Excel::download(new ExportPayments, 'Payments_Report.xlsx');
    }
    public function invoices_export() 
    {
        $user = Auth::user();
        return Excel::download(new ExportInvoices, 'Invoices.xlsx');
    }
    public function invoices_report_export() 
    {
        $user = Auth::user();
        return Excel::download(new ExportInvoicesReport, 'Invoices_Report.xlsx');
    }
    public function applications_export() 
    {
        $user = Auth::user();
        return Excel::download(new ExportApplications, 'Applications.xlsx');
    }
    public function applications_report_export() 
    {
        $user = Auth::user();
        return Excel::download(new ExportApplicationsReport, 'Applications_Report.xlsx');
    }


}
