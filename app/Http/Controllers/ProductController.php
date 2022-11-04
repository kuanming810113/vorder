<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\GoodsProductStyleMap;
use App\Models\Product;
use App\Models\ProductStyle;

use Auth;
use DB;


class ProductController extends Controller
{
	public function index(Request $request)
	{
    	$input = $request->all();

    	$main = Product::select('products.*')
        ->leftJoin('product_styles', 'product_styles.product_id', '=', 'products.id')
        ->leftJoin('goods_product_style_maps', 'goods_product_style_maps.product_style_id', '=', 'product_styles.id')
        ->groupBy('products.id')
        ->where('products.store_id' , Auth::user()->store_id);

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
                        $query->orWhere('products.name', 'like', '%' . $search . '%');
                        $query->orWhere('product_styles.name', 'like', '%' . $search . '%');
                    });
        }
        //進階搜尋
        $search_plus = $input['search_plus'];
        $main = $main->Where(function ($query) use ($search_plus) {
                    if (isset($search_plus['name'])) {
                        $query = $query->Where('products.name', 'like', '%' . $search_plus['name'] . '%');
                    }
                    if (isset($search_plus['amount'])) {
                        $query = $query->Where('product_styles.amount','<=',  $search_plus['amount'] )
                        ->Where('product_styles.amount','<>',  -99 );
                    }
                    if (isset($search_plus['goods'])) {
                         $query = $query->Where('goods_product_style_maps.goods_id', $search_plus['goods'] );
                    }

                });



        $main = $main->paginate($input['per_page']);

        foreach ($main as $key => $val) {
            $product_style = ProductStyle::
            where('store_id' , Auth::user()->store_id)
            ->where('product_id',$val['id'])
            ->get()->toArray();

            $main[$key]['product_style'] = $product_style;
        }

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

                $product = Product::where('store_id' , Auth::user()->store_id)->where('id',$input['id'])->first();

                $product_style = ProductStyle::
                where('store_id' , Auth::user()->store_id)
                ->where('product_id',$input['id'])
                ->get();

                $data['product'] = $product;
                $data['product_style'] = $product_style;
                
                return response()->json(['result' => 'success','data' => $data],200);

                break;

            case 'all':
                $main = Product::where('store_id' , Auth::user()->store_id)->get();
                
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

            Product::insert([
                'store_id'=>Auth::user()->store_id,
                'name'=>$input['name'],
                'intro'=>$input['intro'],
                'weight'=>$input['weight'],
                'volume'=>$input['volume'],
                'created_at'=>now(),
            ]);

            $last_id = DB::getPdo()->lastInsertId();

            foreach ($input['style'] as $key => $val) {
                ProductStyle::insert([
                    'store_id'=>Auth::user()->store_id,
                    'product_id'=>$last_id,
                    'name'=>$val['name'],
                    'amount'=>$val['amount'],
                    'created_at'=>now(),
                ]);
            }

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

                    Product::
                    where('id',$input['id'])
                    ->where('store_id',Auth::user()->store_id)
                    ->update([
		                'name'=>$input['name'],
		                'intro'=>$input['intro'],
		                'weight'=>$input['weight'],
		                'volume'=>$input['volume'],
                        'updated_at'=>now()
                    ]);

                    foreach ($input['style'] as $key => $val) {
                        if(isset($val['id'])){
                            ProductStyle::
                            where('id',$val['id'])
                            ->where('store_id',Auth::user()->store_id)
                            ->update([
                                'name'=>$val['name'],
                                'amount'=>$val['amount'],
                                'updated_at'=>now(),
                            ]);
                        }else{
                            ProductStyle::insert([
                                'store_id'=>Auth::user()->store_id,
                                'product_id'=>$input['id'],
                                'name'=>$val['name'],
                                'amount'=>$val['amount'],
                                'created_at'=>now(),
                            ]);
                        }

                    }

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

        try {
            DB::beginTransaction();

            $check = GoodsProductStyleMap::where('store_id',Auth::user()->store_id)->where('product_id',$input['id'])->count();

            if($check > 0){
                return response()->json(['result' => 'goods_exist'],400);
            }

	        Product::where('store_id',Auth::user()->store_id)->where('id',$input['id'])->delete();
	        ProductStyle::where('store_id',Auth::user()->store_id)->where('product_id',$input['id'])->delete();

	        DB::commit();

            return response()->json(['result' => 'success'],200);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['result' => 'error','data' => $e->getMessage()],400);
        }
	}
}
