<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Store;

use Auth;
use Hash;
use DB;

class UserController extends Controller
{
	public function register(Request $request)
	{
    	$input = $request->all();

        $validator = Validator::make($input, [
            'store_name' => 'required',
            'password' => 'required',
            'email'=>'required|unique:users,email'
        ],[
            "email.unique" => 'email_unique',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()], 400);
        }

        User::insert([
            'email'=>$input['email'],
            'name'=>$input['user_name'],
            'password'=>Hash::make($input['password']),
            'created_at'=> NOW()
        ]);

        $last_id = DB::getPdo()->lastInsertId();

        Store::insert([
            'name'=>$input['store_name'],
            'user_id'=> $last_id,
            'url'=> md5(uniqid()),
            'created_at'=> NOW()
        ]);        

        return response()->json(['result' => 'success'], 200);
	}
}
