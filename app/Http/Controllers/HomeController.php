<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function store(Request $request)
    {
        $validate = $request->validate([
            'full_name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);


        $data = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $data->createToken('MyApp')->plainTextToken;

        return response()->json(['success'=>'true','message'=>'Add Data Successfully','token'=>$token],200);
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if($data = User::where('email',$request->email)->first()){
            if(Hash::check($request->password, $data->password)){

                $token = $data->createToken('MyApp')->plainTextToken;
    
                return response()->json(['success'=>'true','message'=>'Login Successfully','token'=>$token],200);
            }
        }
        
        return response()->json(['success'=>'false','message'=>'Invalid Email Id And Password'],404);

    }

    public function logout()
    {
        $id = auth('sanctum')->user()->id;

        $data = User::find($id);

        $data->tokens()->delete();

        return response()->json(['success'=>'true','message'=>'Logout Successfully'],200);

    }

    public function addadd(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'city' => 'required',
            'street' => 'required',
            'house_number' => 'required',
            'complete_address' => 'required',
            'place' => 'required',
        ]);

        $uid = auth('sanctum')->user()->id;

        $data = Address::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'street' => $request->street,
            'house_number' => $request->house_number,
            'complete_address' => $request->complete_address,
            'place' => $request->place,
            'user_id' => $uid,
        ]);

        return response()->json(['success'=>'true','message'=>'Add Address Successfully'],200);
    }

    public function getadd()
    {
        $uid = auth('sanctum')->user()->id;
        
        $data = Address::where('user_id',$uid)->get();

        return response()->json(['success'=>'true','message'=>$data],200);
    }

    public function changepassword(Request $request)
    {
        $validate = $request->validate([
            'oldpassword' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if(Hash::check($request->oldpassword,auth('sanctum')->user()->password)){
            $updpw = Hash::make($request->confirm_password);
        }else{
            return response()->json(['success'=>'false','message'=>'Your Old Password Is Wrong'],403);
        }

        $user = User::find(auth('sanctum')->user()->id);
        $user->password = $updpw;
        $user->save();

        return response()->json(['success'=>'true','message'=>'Your Password Is Change Successfully'],200);
    }



    public function sendotp(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required'
        ]);


        $email = $request->email;

        $us = User::where('email',$email)->first();
        $otp = rand(111111,999999);
        $us->otpcode = $otp;
        $us->save();

        $details = [
            'title' => 'Mail from Ecoomerce',
            'body' => 'your otp '.$otp,
        ];

        Mail::to($email)->send(new \App\Mail\MyTestMail($details));

        return response()->json(['message'=>'okay'],200);
    }

    public function otpsubmit(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required',
            'otp' => 'required',
            'password' => 'required',
            'confirmpassword' => 'required|same:password',
        ]);

        $email = $request->email;
        $otp = $request->otp;
        $pw = $request->password;

        $us = User::where('email',$email)->first();

        if($us->otpcode == $otp){

            $us->password = Hash::make($pw);
            $us->otpcode = 0;
            $us->save();

            return response()->json(['success'=>'true','message'=>'Your Password Change Successfully'],200);

        }else{
            return response()->json(['success'=>'false','message'=>'Wrong Otp'],200);
        }
    }
}
