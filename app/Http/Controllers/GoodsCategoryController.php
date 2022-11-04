<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\GoodsCategory;
use App\Models\Goods;
use Auth;
use DB;

class GoodsCategoryController extends Controller
{
	public function index(Request $request)
	{
    	$input = $request->all();

        $main = GoodsCategory::select('goods_categories.*')
        ->where('goods_categories.store_id' , Auth::user()->store_id)
        ->groupBy('goods_categories.id')
        ->orderBy('goods_categories.sort','desc');


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
                        $query->orWhere('goods_categories.name', 'like', '%' . $search . '%');
                    });
        }
        //進階搜尋
        $search_plus = $input['search_plus'];
        $main = $main->Where(function ($query) use ($search_plus) {
                    if (isset($search_plus['name'])) {
                        $query = $query->Where('goods_categories.name', 'like', '%' . $search_plus['name'] . '%');
                    }
                    if (isset($search_plus['is_show'])) {
                        $query = $query->Where('goods_categories.is_show',  $search_plus['is_show']);
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

                $main = GoodsCategory::where('store_id' , Auth::user()->store_id)->where('id',$input['id'])->first();

                
                return response()->json(['result' => 'success','data' => $main],200);

                break;

            case 'all':
                $main = GoodsCategory::where('store_id' , Auth::user()->store_id)->get();
                
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

            GoodsCategory::insert([
                'store_id'=>Auth::user()->store_id,
	            'name'=>$input['name'],
	            'is_show'=>$input['is_show'] ?? 1,
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

                    GoodsCategory::
                    where('id',$input['id'])
                    ->where('store_id',Auth::user()->store_id)
                    ->update([
			            'name'=>$input['name'],
			            'is_show'=>$input['is_show'] ?? 1,
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

        $check = Goods::where('store_id',Auth::user()->store_id)->where('goods_category_id',$input['id'])->count();

        if($check > 0){
            return response()->json(['result' => 'goods_exist'],400);
        }

	    GoodsCategory::where('store_id',Auth::user()->store_id)->where('id',$input['id'])->delete();

	    return response()->json(['result' => 'success'],200);
	}



}
