<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Account;

use Auth;
use DB;


class AccountController extends Controller
{
	public function index(Request $request)
	{
    	$input = $request->all();

        $main = Account::select('accounts.*','companies.name as company_name')
        ->leftJoin('companies', 'companies.id', '=', 'accounts.company_id')
        ->where('accounts.store_id' , Auth::user()->store_id)
        ->orderBy('sort','desc');


        if (isset($input['sort_by'])) {
        	if($input['sort_desc'] == 'true'){
        		$main = $main->orderBy($input['sort_by'],'desc');
        	}else if($input['sort_desc'] == 'false'){
        		$main = $main->orderBy($input['sort_by'],'asc');
        	}
        }

        if (isset($input['search'])) {
            $search = $input['search'];
            $main = $main->Where(function ($query) use ($search) {
                        $query->orWhere('accounts.name', 'like', '%' . $search . '%');
                        $query->orWhere('companies.name', 'like', '%' . $search . '%');
                        $query->orWhere('accounts.price', 'like', '%' . $search . '%');
                        $query->orWhere('accounts.date', 'like', '%' . $search . '%');
                        $query->orWhere('accounts.remark', 'like', '%' . $search . '%');
                    });
        }
        //進階搜尋
        $search_plus = $input['search_plus'];
        $main = $main->Where(function ($query) use ($search_plus) {
                    if (isset($search_plus['name'])) {
                        $query = $query->Where('accounts.name', 'like', '%' . $search_plus['name'] . '%');
                    }
                    if (isset($search_plus['company_id'])) {
                        $query = $query->Where('accounts.company_id', $search_plus['company_id']);
                    }
                    if (isset($search_plus['start_date'])) {
                        $query = $query->Where('accounts.date', '>=', $search_plus['start_date']);
                    }
                    if (isset($search_plus['end_date'])) {
                        $query = $query->Where('accounts.date', '<=', $search_plus['end_date']);
                    }
                    if (isset($search_plus['bigger_price'])) {
                        $query = $query->Where('accounts.price', '>=', $search_plus['bigger_price']);
                    }
                    if (isset($search_plus['smaller_price'])) {
                        $query = $query->Where('accounts.price', '<=', $search_plus['smaller_price']);
                    }
                });



        $main = $main->paginate($input['per_page']);

        return response()->json(['result' => 'success','data' => $main],200);
	}

	public function get(Request $request,$action_type)
	{
    	$input = $request->all();

        switch ($action_type) {
            case 'id':

                $validator = Validator::make($input, [
                    'id' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
                }

                $main = Account::where('store_id' , Auth::user()->store_id)->where('id',$input['id'])->first();

                
                return response()->json(['result' => 'success','data' => $main],200);

                break;

            case 'all':
                $main = Account::where('store_id' , Auth::user()->store_id)->get();
                
                return response()->json(['result' => 'success','data' => $main],200);

                break;
            default:
                return response()->json(['result' => 'error' ],400);
                break;
        }
	}

	public function insert(Request $request)
	{
    	$input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
        }

        try {
            DB::beginTransaction();

            Account::insert([
                'store_id'=>Auth::user()->store_id,
	            'name'=>$input['name'],
	            'company_id'=>$input['company_id'],
	            'price'=>$input['price'] ?? 0,
	            'date'=>$input['date'],
	            'remark'=>$input['remark'],
	            'created_at'=>now(),
            ]);

            DB::commit();

            return response()->json(['result' => 'success'],200);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['result' => 'error','data' => $e->getMessage()],400);
        }

	}

    public function update(Request $request,$action_type)
    {
        $input = $request->all();

        switch ($action_type) {
            case 'id':
		        $validator = Validator::make($input, [
		        	'id' => 'required',
		            'name' => 'required',
		        ]);

		        if ($validator->fails()) {
		            return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
		        }

                try {
                    DB::beginTransaction();

                    Account::
                    where('id',$input['id'])
                    ->where('store_id',Auth::user()->store_id)
                    ->update([
			            'name'=>$input['name'],
			            'company_id'=>$input['company_id'],
			            'price'=>$input['price'] ?? 0,
			            'date'=>$input['date'],
			            'remark'=>$input['remark'],
	                    'updated_at'=>now()
                    ]);

                    DB::commit();

                    return response()->json(['result' => 'success'],200);

                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => 'error','data' => $e->getMessage()],400);
                }

                break;
            default:
                return response()->json(['result' => 'error'],400);
                break;
        }

        return response()->json(['result' => 'success'],200);
    }

	public function delete(Request $request)
	{
    	$input = $request->all();

        $validator = Validator::make($input, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()] , 400);
        }

	    Account::where('store_id',Auth::user()->store_id)->where('id',$input['id'])->delete();

	    return response()->json(['result' => 'success'],200);
	}
}
