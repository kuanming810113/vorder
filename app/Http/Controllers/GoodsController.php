<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Goods;
use App\Models\GoodsProductStyleMap;
use App\Models\GoodsCombo;
use App\Models\ProductStyle;
use App\Models\Product;
use Auth;
use DB;

class GoodsController extends Controller
{
    public function index(Request $request)
    {
    	$input = $request->all();


    	$main = Goods::select('goods.*','goods_categories.name as goods_categories_name')
    	->leftJoin('goods_categories', 'goods.goods_category_id', '=', 'goods_categories.id')
    	// ->leftJoin('product_finish_maps', 'product_finish_maps.product_finish_id', '=', 'product_finishes.id')
    	// ->leftJoin('products', 'product_finish_maps.product_id', '=', 'products.id')
    	->where('goods.store_id',Auth::user()->store_id)
    	->groupBy('goods.id')
    	->orderBy('goods.sort','desc')
        ->orderBy('goods.created_at','desc');


        //全局搜尋
        // if (isset($input['search_global_text'])) {
        //     $search = $input['search_global_text'];
        //     $main = $main->Where(function ($query) use ($search) {
        //                 $query->orWhere('product_types.name', 'like', '%' . $search . '%')
        //                       ->orWhere('product_finishes.name', 'like', '%' . $search . '%')
        //                       ->orWhere('products.name', 'like', '%' . $search . '%');
        //             });
        // }

        //進階搜尋
        // if(isset($input['search_plus'])){
        //     $search_arr = [
        //         'product_type_id'=>$input['product_type_id'] ?? null,
        //         'product_id'=>$input['product_id'] ?? null,
        //         'name'=>$input['name'] ?? null,
        //         'is_show'=>$input['is_show'] ?? null,
        //     ];

        //     $main = $main->Where(function ($query) use ($search_arr) {
        //                 if (isset($search_arr['product_type_id'])) {
        //                     $query = $query->Where('product_finishes.product_type_id',  $search_arr['product_type_id'] );
        //                 }
        //                 if (isset($search_arr['product_id'])) {
        //                     $query = $query->Where('product_finish_maps.product_id',  $search_arr['product_id'] );
        //                 }
        //                 if (isset($search_arr['name'])) {
        //                     $query = $query->Where('product_finishes.name',  $search_arr['name'] );
        //                 }
        //                 if (isset($search_arr['is_show'])) {
        //                     $query = $query->Where('product_finishes.is_show', $search_arr['is_show']);
        //                 }
        //             });
        // }

        $main = $main->paginate(20);


    	// foreach ($main as $key => $val) {
    	// 	$product = ProductFinishMap::select('products.id','products.name','products.estimated_cost')
    	// 	->leftJoin('products', 'products.id', '=', 'product_finish_maps.product_id')
    	// 	->where('product_finish_maps.user_id',Auth::id())
    	// 	->where('product_finish_maps.product_finish_id',$val['id'])
     //        ->groupBy('products.id')
    	// 	->get()->toArray();
    	
    	// 	$main[$key]['product'] = $product;

     //        //狀態 : 有無缺貨
     //        $main[$key]['product_style_amount'] = ProductFinishMap::select('*')
     //        ->leftJoin('product_styles', 'product_styles.product_id', '=', 'product_finish_maps.product_id')
     //        ->where('product_finish_maps.user_id',Auth::id())
     //        ->where('product_finish_maps.product_finish_id',$val['id'])
     //        ->where('product_styles.amount','<=', 0)
     //        ->where('product_styles.amount','<>', -99)
     //        ->groupBy('product_styles.id')
     //        ->count();
    	// }

    	$data['all'] = $main;


    	return response()->json(['result' => 'success','data' => $main],200);
    }



	public function insert(Request $request)
	{
    	$input = $request->all();

        $validator = Validator::make($input, [
        	'basic' => 'required',
            'goods' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
        }

        try {
            DB::beginTransaction();

            Goods::insert([
                'store_id'=>Auth::user()->store_id,
	            'goods_category_id'=>$input['basic']['goods_category_id'],
	            'name'=>$input['basic']['name'],
	            'price'=>$input['basic']['price'],
	            'is_show'=>$input['basic']['is_show'],
	            'sort'=>$input['basic']['sort'],
	            'created_at'=>now(),
            ]);

        	$last_id = DB::getPdo()->lastInsertId();

            foreach ($input['goods'] as $key => $val) {
            	foreach ($val['product_data'] as $key1 => $val1) {
	            	foreach ($val1['product_style'] as $key2 => $val2) {
			            GoodsProductStyleMap::insert([
			                'store_id'=>Auth::user()->store_id,
				            'goods_id'=>$last_id,
				            'goods_combo_id'=>$val['combo_id'],
				            'product_id'=>$val1['product_id'],
				            'product_style_id'=>$val2['id'],
				            'extra_price'=>$val2['extra_price'],
				            'created_at'=>now(),
			            ]);
	            	}
            	}
            }

            DB::commit();

            return response()->json(['result' => 'success'],200);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['result' => 'error','data' => $e->getMessage()],400);
        }

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

                $basic = Goods::where('store_id' , Auth::user()->store_id)->where('id',$input['id'])->first();

                if(empty($basic)){
                    return response()->json(['result' => 'error'],400);
                }

                $GoodsProductStyleMap = GoodsProductStyleMap::
                where('store_id' , Auth::user()->store_id)
                ->where('goods_id' , $input['id'])
                ->get()->toArray();

                $GoodsProductStyleMap_arr = [];
                foreach ($GoodsProductStyleMap as $key => $val) {
                    $GoodsProductStyleMap_arr[$val['goods_combo_id']][$val['product_id']][] = ['product_style_id' => $val['product_style_id'] , 'extra_price'=> $val['extra_price']];
                }

                $result = [];

                foreach ($GoodsProductStyleMap_arr as $key => $val) {

                    $GoodsCombo = GoodsCombo::select('id as combo_id','name as combo_name')
                    ->where('store_id' , Auth::user()->store_id)
                    ->where('id',$key)
                    ->first();

                    $result[$key]['combo_id'] = $GoodsCombo['combo_id'];
                    $result[$key]['combo_name'] = $GoodsCombo['combo_name'];

                    foreach ($val as $key1 => $val1) {

                        $Product = Product::select('id as product_id','name as product_name')
                        ->where('store_id' , Auth::user()->store_id)
                        ->where('id',$key1)
                        ->first();

                        $result[$key]['product_data'][$key1]['product_id'] =  $Product['product_id'];
                        $result[$key]['product_data'][$key1]['product_name'] =  $Product['product_name'];

                        foreach ($val1 as $key2 => $val2) {

                            $ProductStyle = ProductStyle::select('id as product_style_id','name as product_style_name')
                            ->where('store_id' , Auth::user()->store_id)
                            ->where('id',$val2['product_style_id'])
                            ->first();

                            $result[$key]['product_data'][$key1]['product_style'][$key2]['id'] =  $ProductStyle['product_style_id'];
                            $result[$key]['product_data'][$key1]['product_style'][$key2]['name'] =  $ProductStyle['product_style_name'];
                            $result[$key]['product_data'][$key1]['product_style'][$key2]['extra_price'] =  $val2['extra_price'];
                        }
                    }
                }
                
                foreach ($result as $key => $val) {
                    $result[$key]['product_data'] = array_values($val['product_data']);
                }
                
                $data['goods'] = array_values($result);
                $data['basic'] = $basic;
                return response()->json(['result' => 'success','data' => $data],200);

                break;

            default:
                return response()->json(['result' => 'error' ],400);
                break;
        }
    }


}
