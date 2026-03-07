<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applications;
use App\Models\User;
use App\Models\Activities;
use Auth;
use App\Models\Clients;
use App\Models\Dependants;
use Carbon\Carbon;


class ApplicationController extends Controller
{


    public function addClientApplication(Request $request)
    {

        $user = Auth::user();
        $client = Clients::find($request->client_id);
        $subscriber = User::find($client->subscriber_id);
        $application = new Applications();
        $application->client_id = $request->client_id;
        $application->subscriber_id =  $subscriber->id;
        $application->application_id = $this->job_id();
        $application->application_category = $subscriber->category;
        $application->application_subcategory = $subscriber->sub_category;
        $application->application_name = $request['job_role'];
        $application->application_country =  $client->country;
        $application->visa_country = $request['visa_country'];
        $application->application_detail = $request['job_detail'];
        $application->application_program = $request['study_program'];
        $application->application_status = $request['job_status'];
        $application->start_date = $request['job_open_date'];
        $application->end_date = $request['job_completion_date'];
        $application->save();
        $activity = new Activities();
        $activity->subscriber_id =  $subscriber->id;
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "New Application Added";
        $activity->activity_detail = "New Application of " . $request->job_role . " added by " . $user->name . " at " . date('d M, Y H:i:s');
        $activity->activity_icon = "user.png";
        $activity->save();
        return response()->json(['success' => true, 'message' => 'New Application Added Successfully']);
    }
    public function job_id()
    {
        $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $id = "";
        for ($i = 0; $i < 8; $i++) {
            $id = $id . $ch[rand(0, strlen($ch) - 1)];
        }
        return $id;
    }

    public function getClient()
    {
         $query = new Clients ();
         if(auth()->user()->user_type != 'admin'){
            $userId = (auth()->user()->user_type == 'Subscriber') ? auth()->id() : auth()->user()->add_by;
            $query = $query->where('subscriber_id',$userId);
         }
        $clients = $query->get();
        return response()->json(['limit' => 'not full', 'clients' => $clients]);
    }
    public function addClientDependent(Request $request)
    {
        $user = auth()->user();
        $data = $request->except('_token');
        $client = Clients::find($request->client_id);
        $data['dob'] = Carbon::parse($data['dob'])->format('Y-m-d');
        $data['client_id'] = $client->id;
        $data['subscriber_id'] = $client->subscriber_id;
        Dependants::create($data);

        $activity = new Activities();
        $activity->subscriber_id = $request->subscriber_id;
        $activity->user_id =  $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "New Dependant Added";
        $activity->activity_detail = "New Dependent of " . $client->name . " added by " . $user->name . " at " . date('d M, Y H:i:s');
        $activity->activity_icon = "user.png";
        $activity->save();
        return response()->json(['success' => true, 'message' => 'New Dependant Added Successfully']);
    }
    public function manage_dependants()
    {
        $user = Auth::user();
        $page = 'Dependents';
        $subscribers = User::where('user_type', '=', 'Subscriber')->get();
        $dependents = Dependants::orderBy('created_at', 'desc')->get();
        $clients = Clients::get();
        return view('admin.manage_dependant', compact('dependents', 'user', 'page', 'subscribers', 'clients'));
    }
    public function subscriber_dependents()
    {

        $subscriber = auth()->user()->user_type == 'Subscriber' ? auth()->id() : auth()->user()->added_by;
        $user = Auth::user();
        $page = 'Dependents';
        $clients = Clients::where('subscriber_id',$subscriber)->get();
        $dependents = Dependants::where('subscriber_id', $subscriber)->orderBy('created_at', 'desc')->get();
        return view('web.manage_dependant', compact('dependents', 'user', 'page','clients'));
    }
    public function getDependants($id)
    {

        $dependent = Dependants::find($id);

        if ($dependent) {
            return response()->json([
                'success' => true,
                'data' => $dependent,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Dependent not found',
        ]);
    }
    public function updateDependant(Request $request, $id)
    {
        // dd($request->all(), $id);
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Prefer Not To Say',
            'relation' => 'required|string|max:50',
            'dob' => 'required|date|before:today',
            'passport_no' => 'nullable|string|max:14',
            // 'subscriber_id' => 'required|integer|exists:users,id',
            'client_id' => 'required|integer|exists:clients,id',
        ]);

        // Find the dependant by ID
        $dependant = Dependants::find($id);
        $client = Clients::find($request->client_id);
        if (!$dependant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dependant not found'
            ], 404);
        }

        // Update the dependant's data
        $dependant->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'relation' => $request->relation,
            'dob' => $request->dob,
            'passport_no' => $request->passport_no,
            'subscriber_id' => $client->subscriber_id ?? auth()->id(),
            'client_id' => $request->client_id,
        ]);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Dependant updated successfully',
            'data' => $dependant
        ]);
    }
    public function deleteDependant($id)
    {
        // Find the dependant by ID
        $dependant = Dependants::find($id);

        if (!$dependant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dependant not found'
            ], 404);
        }

        // Delete the dependant
        $dependant->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Dependant deleted successfully'
        ]);
    }
}
