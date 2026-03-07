<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\VisaEnquiry;
use App\Models\EnquiryResidencyHistory;
use App\Models\EnquiryTravelHistory;
use App\Models\EnquiryRefusalHistory;
use App\Models\EnquiryWorkExperience;
use App\Models\EnquiryChild;
use App\Models\EnquiryFundingSource;

class VisaEnquiryController extends Controller
{

    public function store(Request $request)
    {

        DB::beginTransaction();

        try{

            $enquiry = VisaEnquiry::create([
                'subscriber_id' => $request->subscriber_id,
                'full_name' => $request->full_name,
                'dob' => $request->dob,
                'email' => $request->email,
                'contact_no' => $request->contact_no,
                'marital_status' => $request->marital_status,
                'address' => $request->address,

                'country_pref_1' => $request->country_pref[0] ?? null,
                'country_pref_2' => $request->country_pref[1] ?? null,
                'country_pref_3' => $request->country_pref[2] ?? null,

                'visa_category' => $request->visa_category,

                'qualification' => $request->qualification,
                'institution' => $request->institution,
                'passing_year' => $request->passing_year,
                'grade' => $request->grade,

                'english_test' => $request->english_test,
                'overall_score' => $request->overall_score,
                'test_date' => $request->test_date,

                'spouse_name' => $request->spouse_name,
                'spouse_email' => $request->spouse_email,
                'spouse_dob' => $request->spouse_dob,
                'spouse_contact' => $request->spouse_contact,

                'signature' => $request->signature,
            ]);


            /* Residency History */

            if($request->res_country){
                foreach($request->res_country as $key=>$country){

                    EnquiryResidencyHistory::create([
                        'enquiry_id' => $enquiry->id,
                        'country' => $country,
                        'duration' => $request->res_duration[$key] ?? null,
                        'visa_category' => $request->res_visa[$key] ?? null
                    ]);

                }
            }


            /* Travel History */

            if($request->travel_country){
                foreach($request->travel_country as $key=>$country){

                    EnquiryTravelHistory::create([
                        'enquiry_id' => $enquiry->id,
                        'country' => $country,
                        'duration' => $request->travel_duration[$key] ?? null
                    ]);

                }
            }


            /* Visa Refusal */

            if($request->refusal_country){
                foreach($request->refusal_country as $key=>$country){

                    EnquiryRefusalHistory::create([
                        'enquiry_id' => $enquiry->id,
                        'country' => $country,
                        'refusal_date' => $request->refusal_date[$key] ?? null,
                        'refusal_reason' => $request->refusal_reason[$key] ?? null
                    ]);

                }
            }


            /* Work Experience */

            if($request->job_title){
                foreach($request->job_title as $key=>$job){

                    EnquiryWorkExperience::create([
                        'enquiry_id' => $enquiry->id,
                        'job_title' => $job,
                        'employer' => $request->employer[$key] ?? null,
                        'work_country' => $request->work_country[$key] ?? null,
                        'joining_date' => $request->joining_date[$key] ?? null
                    ]);

                }
            }


            /* Children */

            if($request->child_name){
                foreach($request->child_name as $key=>$child){

                    EnquiryChild::create([
                        'enquiry_id' => $enquiry->id,
                        'child_name' => $child,
                        'child_age' => $request->child_age[$key] ?? null,
                        'child_gender' => $request->child_gender[$key] ?? null,
                        'child_dob' => $request->child_dob[$key] ?? null
                    ]);

                }
            }


            /* Funding Sources */

            if($request->funding){
                foreach($request->funding as $fund){

                    EnquiryFundingSource::create([
                        'enquiry_id' => $enquiry->id,
                        'funding_source' => $fund
                    ]);

                }
            }

            DB::commit();

            return redirect()->back()->with('success','Enquiry submitted successfully.');

        }catch(\Exception $e){

            DB::rollBack();

            return redirect()->back()->with('error','Something went wrong. Please try again.');

        }

    }

}