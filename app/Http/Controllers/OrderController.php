<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;


use Auth;
use DB;

class OrderController extends Controller
{
	public function index(Request $request)
	{
    	$input = $request->all();

    	$main = Order::select('orders.*')
        ->where('orders.store_id' , Auth::user()->store_id);

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
                        $query->orWhere('orders.no', 'like', '%' . $search . '%');
                        $query->orWhere('orders.remark', 'like', '%' . $search . '%');
                    });
        }
        //進階搜尋
        $search_plus = $input['search_plus'];
        $main = $main->Where(function ($query) use ($search_plus) {
                    // if (isset($search_plus['name'])) {
                    //     $query = $query->Where('products.name', 'like', '%' . $search_plus['name'] . '%');
                    // }
                    // if (isset($search_plus['amount'])) {
                    //     $query = $query->Where('product_styles.amount','<=',  $search_plus['amount'] )
                    //     ->Where('product_styles.amount','<>',  -99 );
                    // }
                    // if (isset($search_plus['goods'])) {
                    //      $query = $query->Where('goods_product_style_maps.goods_id', $search_plus['goods'] );
                    // }
                });



        $main = $main->paginate($input['per_page']);


        return response()->json(['result' => 'success','data' => $main],200);
	}

}
