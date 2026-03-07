<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;
use App\Models\User;

class EmailController extends Controller
{
    public function sendEmail()
    {
        // $mailInfo = new \stdClass();
        // $mailInfo->recieverName = "John Defoe";
        // $mailInfo->sender = "Mike";
        // $mailInfo->senderCompany = "CodeInnovers Technologies";
        // $mailInfo->to = "johndefoe@email.com";
        // $mailInfo->subject = "Support- Team CodeInnovers";
        // $mailInfo->name = "Mike";
        // $mailInfo->cc = "ci@email.com";
        // $mailInfo->bcc = "jim@email.com";

        // Mail::to("johndefoe@email.com")
        //    ->send(new LaraEmail($mailInfo));

        // $notifi = $notification;
        $maildata = new \stdClass();
        $maildata->name = "Sanju";
        $maildata->email = "sanju@gmail.com";
        $maildata->otp = "12345";
        return view('web.emailtemplate',compact('maildata'));
        // Mail::to("s.k.sangwalvip@gmail.com")->send(new EmailVerification($maildata));
        //     if (Mail::failures()) {
        //         echo 'Sorry! Please try again latter';
        //     }else{
        //         echo 'Great! Successfully send in your mail';
        //     }
    }

    public function password_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email', // Check if the email exists in the 'users' table
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'email.exists' => 'The provided email does not match our records.', // Custom error message
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'g-recaptcha-response.required' => 'Please complete the CAPTCHA verification.',
        ]);

        try {
            $email = $request->email;
            $otp = rand(10000, 99999);
            $user = User::where('email', $email)->first();

            // Prepare mail data
            $maildata = new \stdClass();
            $maildata->email = $email;
            $maildata->name = $user->name;
            $maildata->password = "password";
            $maildata->otp = $otp;

            // Send the email
            Mail::to($email)->send(new EmailVerification($maildata));


            // Save OTP to user record
            $user->email_otp = $otp;
            $user->email_otp_created_at = now(); // Optional: track when the OTP was created
            $user->save();

            // Send success response
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email!',
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions and send an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.',
                'error' => $e->getMessage(), // Optional: remove in production for security
            ], 500);
        }
    }

}
