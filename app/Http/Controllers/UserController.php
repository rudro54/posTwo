<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{

    function LoginPage(): View
    {
        return view('pages.auth.login-page');
    }

    function RegistrationPage(): View
    {
        return view('pages.auth.registration-page');
    }

    function SendOtpPage(): View
    {
        return view('pages.auth.send-otp-page');
    }

    function VerifyOtpPage(): View
    {
        return view('pages.auth.verify-otp-page');
    }

    function ResetPasswordPage(): View
    {
        return view('pages.auth.reset-pass-page');
    }

    function DashboardPage(): View
    {
        return view('pages.dashboard.dashboard-page');
    }

    function ProfilePage(): View
    {
        return view('pages.dashboard.profile-page');
    }





    function userRegistration(Request $request)
    {

        try {
            User::create([

                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password')

            ]);
            return response()->json([

                'status' => 'success',
                'message' => 'User Registration Successful'

            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ], 401);

        }
    }

    function userLogin(Request $request)
    {
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->select('id')->first();

        if ($count !== null) {
            $token = JWTToken::CreateToken($request->input('email'), $count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Logged In Successfully',
                'token' => $token

            ], 200)->cookie('token', $token, 60 * 24 * 30);

        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized',

            ], 401);

        }
    }

    function sendOTPCode(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();

        if ($count == 1) {

            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', '=', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'success',
                'message' => "Four Digit OTP Send To Your Email Successfully"

            ], 200);

        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'

            ], 401);
        }
    }

    function verifyOTp(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');

        $count = User::where('email', '=', $email)
            ->where('otp', '=', $otp)->count();

        if ($count == 1) {

            User::where('email', '=', $email)->update([
                'otp' => '0',
            ]);

            $token = JWTToken::CreateTokenForSetPassword($email);
            return response()->json([

                'status' => 'success',
                'message' => "OTP verification successfull!",



            ], 200)->cookie('token', $token);

        } else {

            return response()->json([

                'status' => 'failed',
                'message' => 'unauthorized'


            ], 401);



        }

    }

    function resetPassword(Request $request)
    {


        try {
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email', '=', $email)->update([
                'password' => $password
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password Successfully Changed !!'

            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Something Went Wrong"
            ], 401);

        }

    }

    function userLogout()
    {
        return redirect('/userLogin')->cookie('token', '', -1);
    }

    function userProfile(Request $request)
    {
        try {
            $email = $request->header('email');
            $user = User::where('email', '=', $email)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
                'data' => $user

            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something Went Wrong'

            ], 401);

        }


    }
    function updateProfile(Request $request)
    {

        try {
            $email = $request->header('email');
            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');
            $mobile = $request->input('mobile');
            $password = $request->input('password');

            User::where('email', '=', $email)->update([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'mobile' => $mobile,
                'password' => $password

            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful'

            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something Went Wrong'

            ], 401);

        }

    }
}

