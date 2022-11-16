<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\WarehouseManage;
use App\Models\Account;
use App\Models\ProductStyle;
use App\Models\ChangeInventoryRecord;

use Auth;
use DB;

class WarehouseManageController extends Controller
{
	public function index(Request $request)
	{
    	$input = $request->all();

        $main = WarehouseManage::select('warehouse_manages.*','accounts.name as account_name','companies.name as company_name')
        ->leftJoin('accounts', 'accounts.warehouse_manage_id', '=', 'warehouse_manages.id')
        ->leftJoin('companies', 'companies.id', '=', 'warehouse_manages.company_id')
        ->where('warehouse_manages.store_id' , Auth::user()->store_id)
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
                        $query->orWhere('warehouse_manages.name', 'like', '%' . $search . '%');
                        $query->orWhere('companies.name', 'like', '%' . $search . '%');
                        $query->orWhere('warehouse_manages.price', 'like', '%' . $search . '%');
                        $query->orWhere('warehouse_manages.date', 'like', '%' . $search . '%');
                        $query->orWhere('warehouse_manages.remark', 'like', '%' . $search . '%');
                    });
        }
        //進階搜尋
        $search_plus = $input['search_plus'];
        $main = $main->Where(function ($query) use ($search_plus) {
                    if (isset($search_plus['name'])) {
                        $query = $query->Where('warehouse_manages.name', 'like', '%' . $search_plus['name'] . '%');
                    }
                    if (isset($search_plus['type'])) {
                        $query = $query->Where('warehouse_manages.type', $search_plus['type']);
                    }
                    if (isset($search_plus['company_id'])) {
                        $query = $query->Where('accounts.company_id', $search_plus['company_id']);
                    }
                    if (isset($search_plus['start_date'])) {
                        $query = $query->Where('warehouse_manages.date', '>=', $search_plus['start_date']);
                    }
                    if (isset($search_plus['end_date'])) {
                        $query = $query->Where('warehouse_manages.date', '<=', $search_plus['end_date']);
                    }
                    if (isset($search_plus['bigger_price'])) {
                        $query = $query->Where('warehouse_manages.price', '>=', $search_plus['bigger_price']);
                    }
                    if (isset($search_plus['smaller_price'])) {
                        $query = $query->Where('warehouse_manages.price', '<=', $search_plus['smaller_price']);
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

                $main = WarehouseManage::where('store_id' , Auth::user()->store_id)->where('id',$input['id'])->first();

                return response()->json(['result' => 'success','data' => $main],200);

                break;

            case 'all':
                $main = WarehouseManage::where('store_id' , Auth::user()->store_id)->get();
                
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

            WarehouseManage::insert([
                'store_id'=>Auth::user()->store_id,
	            'name'=>$input['name'],
	            'company_id'=>$input['company_id'],
	            'type'=>$input['type'],
	            'price'=>$input['price'],
	            'is_account'=>$input['is_account'],
	            'date'=>$input['date'],
	            'remark'=>$input['remark'],
	            'created_at'=>now(),
            ]);

            $WarehouseManage_last_id = DB::getPdo()->lastInsertId();


            if ($input['is_account'] == 1) {

                switch ($input['type']) {
                    case '1':
                        $type = "一般";
                        break;
                    case '2':
                        $type = "進貨";
                        break;
                    case '3':
                        $type = "退貨";
                        break;
                    case '4':
                        $type = "退料";
                        break;
                    case '5':
                        $type = "報廢";
                        break;
                }

                Account::insert([
                    'store_id'=>Auth::user()->store_id,
                    'warehouse_manage_id'=>$WarehouseManage_last_id,
                    'name'=>$input['name'],
                    'price'=>$input['price'] ?? 0,
                    'date'=>$input['date'],
                    'company_id'=>$input['company_id'],
                    'remark'=>$input['remark'],
                    'created_at'=>now(),
                ]);

            }

	        foreach ($input['product'] as $key => $val) {
	        	foreach ($val['product_style'] as $key1 => $val1) {
                    if ($val1['change_amount'] > 0) {
                        ProductStyle::where('id' , $val1['id'])
                        ->where('store_id',Auth::user()->store_id)
                        ->increment('amount',$val1['change_amount']);

                        ChangeInventoryRecord::insert([
                            'store_id'=>Auth::user()->store_id,
                            'warehouse_manage_id'=>$WarehouseManage_last_id,
                            'product_id'=>$val['product_id'],
                            'product_style_id'=>$val1['id'],
                            'change_amount'=> $val1['change_amount'],
                            'type'=>$input['type'],
                            'created_at'=>now(),
                        ]);
                    }
	        	}
	        }

            WarehouseManage::
            where('id',$WarehouseManage_last_id)
            ->where('store_id',Auth::user()->store_id)
            ->update([
                'change_info'=> json_encode($input['product'],JSON_UNESCAPED_UNICODE),
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

                    WarehouseManage::
                    where('id',$input['id'])
                    ->where('store_id',Auth::user()->store_id)
                    ->update([
			            'name'=>$input['name'],
			            'company_id'=>$input['company_id'],
			            'type'=>$input['type'],
			            'price'=>$input['price'],
			            'is_account'=>$input['is_account'],
			            'date'=>$input['date'],
			            'remark'=>$input['remark'],
	                    'updated_at'=>now()
                    ]);

		            if ($input['is_account'] == 1) {

		                switch ($input['type']) {
		                    case '1':
		                        $type = "一般";
		                        break;
		                    case '2':
		                        $type = "進貨";
		                        break;
		                    case '3':
		                        $type = "退貨";
		                        break;
		                    case '4':
		                        $type = "退料";
		                        break;
		                    case '5':
		                        $type = "報廢";
		                        break;
		                }

		                Account::where('warehouse_manage_id',$input['id'])
                    	->where('store_id',Auth::user()->store_id)
		                ->update([
		                    'warehouse_manage_id'=>$input['id'],
		                    'name'=>$input['name'],
		                    'price'=>$input['price'] ?? 0,
		                    'date'=>$input['date'],
		                    'company_id'=>$input['company_id'],
		                    'remark'=>$input['remark'],
		                    'created_at'=>now(),
		                ]);

		            }

                    DB::commit();

                    return response()->json(['result' => 'success'],200);

                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => 'error','data' => $e->getMessage()],400);
                }

                break;

            case 'recover':
		        $validator = Validator::make($input, [
		        	'id' => 'required'
		        ]);

		        if ($validator->fails()) {
		            return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
		        }

                try {
                    DB::beginTransaction();

	                $WarehouseManage = WarehouseManage::where('id',$input['id'])->where('store_id',Auth::user()->store_id)->first();

	                $WarehouseManage_arr = json_decode($WarehouseManage['change_info'],true);

	                foreach ($WarehouseManage_arr as $key => $val) {
	                	foreach ($val['product_style'] as $key1 => $val1) {

					        ProductStyle::where('id' , $val1['id'])
					        ->where('store_id',Auth::user()->store_id)
					        ->decrement('amount',$val1['change_amount']);

					        $WarehouseManage_arr[$key]['product_style'][$key1]['change_amount'] = 0;
	                	}
	                }

		            WarehouseManage::
		            where('id',$input['id'])
		            ->where('store_id',Auth::user()->store_id)
		            ->update([
		                'change_info'=> json_encode($WarehouseManage_arr,JSON_UNESCAPED_UNICODE),
		            ]);

	                ChangeInventoryRecord::where('warehouse_manage_id',$input['id'])->where('store_id',Auth::user()->store_id)->delete();

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

	    WarehouseManage::where('store_id',Auth::user()->store_id)->where('id',$input['id'])->delete();

	    return response()->json(['result' => 'success'],200);
	}
}
