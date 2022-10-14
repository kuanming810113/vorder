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

        Store::insert([
            'name'=>$input['store_name'],
            'url'=> md5(uniqid()),
            'created_at'=> NOW()
        ]);   

        $last_id = DB::getPdo()->lastInsertId();

        User::insert([
            'store_id'=> $last_id,
            'email'=>$input['email'],
            'name'=>$input['user_name'],
            'password'=>Hash::make($input['password']),
            'created_at'=> NOW()
        ]);

        return response()->json(['result' => 'success'], 200);
	}

    public function login(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required'
        ],[
            "email.required" => 'email_required',
            "password.required" => 'password_required'
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()]);
        }

        $user = User::where('email',$input['email'])->first();

        if (empty($user)) {
            return response()->json(['result'=>'email_error'],400);
        }

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'] ] , $input['remember_me'])) {
            return response()->json(['result' => 'success']);
        }else{
            return response()->json(['result'=>'password_error'],400);
        }

        return response()->json(['result' => 'success']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['result' => 'success']);
    }

    public function get(Request $request,$action_type)
    {
        $input = $request->all();

        switch ($action_type) {
            case 'auth':

                $data = User::select('name')->where('id', Auth::id())->first();

                if (isset($data)) {
                    return response()->json(['result' => 'success','data' => $data]);
                }else{
                    return response()->json(['result' => 'auth_error'],400);
                }

                break;
            case 'id':

                $data = User::select(
                    'users.name as user_name',
                    'users.phone as user_phone',
                    'users.email as user_email',
                    'stores.*')
                ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
                ->where('users.id', Auth::id())->first();

                return response()->json(['result' => 'success','data' => $data]);

                break;
            default:
                return response()->json(['result' => 'error' ]);
                break;
        }
    }

    public function update(Request $request,$action_type)
    {
        $input = $request->all();

        switch ($action_type) {
            case 'user':
                $validator = Validator::make($input, [
                    'name' => 'required',
                    'email' => 'required|unique:users,email,'.Auth::user()->id,
                ],[
                    "email.unique" => 'email_unique',
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
                }

                User::where('id',Auth::id())
                ->update([
                    'name'=>$input['name'],
                    'email'=>$input['email'],
                    'phone'=>$input['phone'],
                    'updated_at'=>now()
                ]);

                break;
            case 'store':
                $validator = Validator::make($input, [
                    'name' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
                }

                Store::where('user_id',Auth::id())
                ->update([
                    'name'=>$input['name'],
                    'intro'=>$input['intro'],
                    'line_url'=>$input['line_url'],
                    'fb_url'=>$input['fb_url'],
                    'ig_url'=>$input['ig_url'],
                    'updated_at'=>now()
                ]);

                break;

            case 'privacy':
                $validator = Validator::make($input, [
                    'password' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
                }

                User::where('id',Auth::id())
                ->update([
                    'password'=>Hash::make($input['password']),
                    'updated_at'=>now()
                ]);

                break;
            default:
                return response()->json(['result' => 'error']);
                break;
        }

        return response()->json(['result' => 'success']);

    }

}
