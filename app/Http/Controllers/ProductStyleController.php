<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Models\ProductStyle;

use Auth;
use DB;

class ProductStyleController extends Controller
{
	public function get(Request $request,$action_type)
	{
    	$input = $request->all();

        switch ($action_type) {
            case 'autocomplete':
		        $data = ProductStyle::select('name')->where('store_id',Auth::user()->store_id)->groupBy('name')->get()->toArray();

		        return response()->json(['result' => 'success','data' => $data ],200);

                break;

            default:
                return response()->json(['result' => 'error' ],400);
                break;
        }
	}
	public function delete(Request $request)
	{
    	$input = $request->all();

        $validator = Validator::make($input, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'validator_error','data' => $validator->errors()->all()],400);
        }

        $style = ProductStyle::where('store_id',Auth::user()->store_id)->where('id',$input['id'])->first();

        $count = ProductStyle::where('store_id',Auth::user()->store_id)->where('product_id',$style['product_id'])->count();

        if ($count == 1) {
            return response()->json(['result' => 'amount_error'],400);
        }

        ProductStyle::where('store_id',Auth::user()->store_id)->where('id',$input['id'])->delete();
        
        return response()->json(['result' => 'success'],200);
	}
}
