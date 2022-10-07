<?php

namespace App\Http\Controllers;

use App\Models\LoggedInHistory;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ConsumerVerification;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    private $userId;

    public function __construct() 
    {
        $this->middleware(function ($request, $next) {
            $this->userId = \Auth::user()->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $data['pageName'] = 'Account';
        $data['user'] = \Auth::user();
        $data['countries'] = Country::orderBy('name', 'ASC')->get(['id', 'name']);
        $data['loggedInHistory'] = LoggedInHistory::where('user_id', $this->userId)->orderBy('id', 'desc')->limit(5)->get();
        return view('account.index', compact('data'));
    }


    public function postProfileUpdate(Request $request)
    {
        $user = User::find($this->userId);
        $user->first_name = $request->input('fname') ??  $user->first_name;
        $user->last_name = $request->input('lname') ?? $user->last_name;
        $user->gender = $request->input('gender') ?? $user->gender;
        $user->dob = $request->input('dob') ??  $user->dob;
        $user->address = $request->input('address') ?? $user->address;
        $user->address_1 = $request->input('address1') ?? $user->address1;
        $user->mobile = $request->input('mobile') ?? $user->mobile;
        $user->country = $request->input('country') ?? $user->country; 
        $user->city = $request->input('city') ?? $user->city;
        $user->state = $request->input('state') ?? $user->state;
        $user->zipcode = $request->input('zipcode') ?? $user->zipcode; 			
        $user->save();
        if ($user->save()) {
            session()->flash('success', 'Profile updated successfully');
            return redirect()->back(); 	
        } else {
            session()->flash('error', 'Profile not getting updated. Please try again later!');
            return redirect()->back(); 
        }
    }


    public function getVerification(Request $request)
    {
        $data['pageName'] = 'Profile Verification';
        $data['user'] = \Auth::user();
        return view('account.verification', compact('data'));
    }

    public function updatekycDoc(Request $request)
    {
        $id = $this->userId;
        $data = $request->all();
        $validate = Validator::make($data, [
            'file1' => 'required|mimes:jpeg,jpg,png|max:10000',
            'file2' => 'required|mimes:jpeg,jpg,png|max:10000',
            'file3' => 'required|mimes:jpeg,jpg,png|max:10000',
        ]);

        if ($validate->fails()) {
            foreach ($validate->messages()->getMessages() as $val => $msg) {
                session()->flash('error', $msg[0]);
                return redirect()->back();
            }
        }

        $update_arr = array();
        $verification = ConsumerVerification::where('user_id', $id)->select('id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'type')->first();
        if ($_FILES['file1']['name'] && $_FILES['file2']['name']) {
            $fileExtensions = array('jpeg', 'jpg', 'png');
            $request1 = $request->file('file1');
            $filename1 =  $this->uploadFiles($request1, 'file1', $fileExtensions);
            $update_arr['id_proof_front'] = $filename1;
            $request2 = $request->file('file2');
            $filename2 =  $this->uploadFiles($request2, 'file2', $fileExtensions);
            $update_arr['id_proof_back'] = $filename2;
            $update_arr['id_status'] = 1;
        } else {
            session()->flash('error', 'Back proof is required');
            return redirect('profile-verification');
        }
        if ($_FILES['file3']['name'] == '') {
            session()->flash('error', 'Selfie proof is required');

            return redirect('profile-verification');
        } else {
            $fileExtensions = array('jpeg', 'jpg', 'png');
            $update_arr['selfie_status'] = 1;
            $request3 = $request->file('file3');

            $update_arr['selfie_proof'] =  $this->uploadFiles($request3, 'file3', $fileExtensions);
        }
        if (!empty($verification->type)) {
            $update_arr['type'] = $verification->type;
        } else {
            $update_arr['type'] = $data['verifytype'];
        }
        $update_arr['user_id'] = $id;
        $update = ConsumerVerification::where('user_id', $id)->updateOrCreate($update_arr);
        if ($update) {
            session()->flash('success', "Your KYC details Updated successfully, Kindly wait until Admin's Approval");
            return redirect('profile-verification');
        } else {
            session()->flash('error', 'Please try again!');
            return redirect('profile-verification');
        }
    }

    public function getNotificationSettings(Request $request)
    {
        $data['pageName'] = 'Profile Verification';
        $data['user'] = \Auth::user();
        return view('account.notifications', compact('data'));
    }
}
