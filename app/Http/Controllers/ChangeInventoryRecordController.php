<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\ChangeInventoryRecord;

use Auth;
use DB;

class ChangeInventoryRecordController extends Controller
{
	public function index(Request $request)
	{
    	$input = $request->all();

    	$main = ChangeInventoryRecord::select(
    		'change_inventory_records.id',
            'change_inventory_records.order_id',
            'change_inventory_records.warehouse_manage_id',
    		'change_inventory_records.type',
    		'change_inventory_records.change_amount',
    		'change_inventory_records.created_at',
    		'product_styles.name as product_style_name',
            'products.id as product_id',
    		'products.name as product_name',
    		//'orders.no as order_no',
    		'warehouse_manages.name as warehouse_manage_name'
    	)
        ->leftJoin('product_styles', 'change_inventory_records.product_style_id', '=', 'product_styles.id')
        ->leftJoin('products', 'change_inventory_records.product_id', '=', 'products.id')
        //->leftJoin('orders', 'change_inventory_records.order_id', '=', 'orders.id')
        ->leftJoin('warehouse_manages', 'change_inventory_records.warehouse_manage_id', '=', 'warehouse_manages.id')
        ->where('change_inventory_records.store_id' , Auth::user()->store_id);



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
                        $query->orWhere('change_inventory_records.change_amount', 'like', '%' . $search . '%');
                        $query->orWhere('change_inventory_records.created_at', 'like', '%' . $search . '%');
                        $query->orWhere('products.name', 'like', '%' . $search . '%');
                        $query->orWhere('warehouse_manages.name', 'like', '%' . $search . '%');
                        $query->orWhere('product_styles.name', 'like', '%' . $search . '%');
                    });
        }

        //進階搜尋
        $search_plus = $input['search_plus'];
        $main = $main->Where(function ($query) use ($search_plus) {
                    if (isset($search_plus['warehouse_manage_name'])) {
                        $query = $query->Where('warehouse_manages.name', 'like', '%' . $search_plus['warehouse_manage_name'] . '%');
                    }
                    if (isset($search_plus['type'])) {
                        $query = $query->Where('change_inventory_records.type', $search_plus['type']);
                    }
                    if (isset($search_plus['product_id'])) {
                        $query = $query->Where('change_inventory_records.product_id', $search_plus['product_id']);
                    }
                    if (isset($search_plus['start_date'])) {
                        $query = $query->Where('change_inventory_records.created_at', '>=', $search_plus['start_date'].' 00:00:00');
                    }
                    if (isset($search_plus['end_date'])) {
                        $query = $query->Where('change_inventory_records.created_at', '<=', $search_plus['end_date'].' 23:59:59');
                    }
                    if (isset($search_plus['bigger_change_amount'])) {
                        $query = $query->Where('change_inventory_records.change_amount', '>=', $search_plus['bigger_change_amount']);
                    }
                    if (isset($search_plus['smaller_change_amount'])) {
                        $query = $query->Where('change_inventory_records.change_amount', '<=', $search_plus['smaller_change_amount']);
                    }
                });



        $main = $main->paginate($input['per_page']);

        return response()->json(['result' => 'success','data' => $main],200);
	}


    public function delete(Request $request,$action_type)
    {
        $input = $request->all();

        switch ($action_type) {
            case 'id':
                $validator = Validator::make($input, [
                    'id' => 'required',
                ],[
                    "id.required" => 'id_required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()]);
                }

                try {
                    DB::beginTransaction();

                    ChangeInventoryRecord::where('store_id' , Auth::user()->store_id)->where('id',$input['id'])->delete();

                    DB::commit();

                    return response()->json(['result' => 'success']);

                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => 'error','data' => $e->getMessage()]);
                }

                break;
            case 'select':
                $validator = Validator::make($input, [
                    'select' => 'required',
                ],[
                    "select.required" => 'select_required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()]);
                }

                try {
                    DB::beginTransaction();

                    ChangeInventoryRecord::where('store_id' , Auth::user()->store_id)->whereIn('id',$input['select'])->delete();

                    DB::commit();

                    return response()->json(['result' => 'success']);

                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => 'error','data' => $e->getMessage()]);
                }




                break;

            default:
                return response()->json(['result' => 'error'],400);
                break;
        }




    }
}