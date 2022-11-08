<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Company;

use Auth;
use DB;

class CompanyController extends Controller
{
	public function index(Request $request)
	{
    	$input = $request->all();

        $main = Company::select('*')
        ->where('store_id' , Auth::user()->store_id)
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
                        $query->orWhere('name', 'like', '%' . $search . '%');
                        $query->orWhere('tax_number', 'like', '%' . $search . '%');
                        $query->orWhere('address', 'like', '%' . $search . '%');
                        $query->orWhere('contact_person', 'like', '%' . $search . '%');
                        $query->orWhere('contact_phone', 'like', '%' . $search . '%');
                        $query->orWhere('contact_url', 'like', '%' . $search . '%');
                        $query->orWhere('remark', 'like', '%' . $search . '%');
                        $query->orWhere('sort', 'like', '%' . $search . '%');
                    });
        }
        //進階搜尋
        $search_plus = $input['search_plus'];
        $main = $main->Where(function ($query) use ($search_plus) {
                    if (isset($search_plus['name'])) {
                        $query = $query->Where('name', 'like', '%' . $search_plus['name'] . '%');
                    }
                    if (isset($search_plus['remark'])) {
                        $query = $query->Where('remark', 'like', '%' . $search_plus['remark'] . '%');
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

                $main = Company::where('store_id' , Auth::user()->store_id)->where('id',$input['id'])->first();

                
                return response()->json(['result' => 'success','data' => $main],200);

                break;

            case 'all':
                $main = Company::where('store_id' , Auth::user()->store_id)->get();
                
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

            Company::insert([
                'store_id'=>Auth::user()->store_id,
	            'name'=>$input['name'],
	            'tax_number'=>$input['tax_number'],
	            'address'=>$input['address'],
	            'contact_person'=>$input['contact_person'],
	            'contact_phone'=>$input['contact_phone'],
	            'contact_url'=>$input['contact_url'],
	            'remark'=>$input['remark'],
	            'sort'=>$input['sort'] ?? 1,
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

                    Company::
                    where('id',$input['id'])
                    ->where('store_id',Auth::user()->store_id)
                    ->update([
			            'name'=>$input['name'],
			            'tax_number'=>$input['tax_number'],
			            'address'=>$input['address'],
			            'contact_person'=>$input['contact_person'],
			            'contact_phone'=>$input['contact_phone'],
			            'contact_url'=>$input['contact_url'],
			            'remark'=>$input['remark'],
			            'sort'=>$input['sort'] ?? 1,
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

	    Company::where('store_id',Auth::user()->store_id)->where('id',$input['id'])->delete();

	    return response()->json(['result' => 'success'],200);
	}
}
