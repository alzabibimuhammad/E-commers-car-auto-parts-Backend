<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Car;
use App\Models\User;
use App\Models\Part;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    //done
    function AddToCart(Request $req){
        $part_id = $req->input('part_id');
        $customer_id = $req->input('customer_id');
        $amount = $req->input('amount');
        $existingCart = Cart::where('part_id', $part_id)->where('customer_id', $customer_id)->first();
        $getPart=Part::where('id',$part_id)->get();
        //cant buy from your self
        if($getPart[0   ]->seller_id!=$customer_id){
        if($amount<=$getPart[0]->amount){
        //check Mony Before Adding To Cart
        $checkMonyBeforeAddingToCart=0;
        $cart400=Cart::where('customer_id',$customer_id)->get();
        $customer=User::where('id',$customer_id)->get();

        if(count($cart400)!=0){
            foreach($cart400 as $cart){
                $checkMonyBeforeAddingToCart+=$cart->totalprice;
        }
        $checkMonyBeforeAddingToCart+=$getPart[0]->price;
        }
        else{
            $checkMonyBeforeAddingToCart+=($getPart[0]->price * $amount);
        }
        //end checking mony
    if($checkMonyBeforeAddingToCart <= $customer[0]->financial_balance){
        if ($existingCart) {
            $existingCart->amount += $amount;

            if ($existingCart->amount <= 0) {
                return response()->json(['The entered amount is less than 0'],400);
            }

            $existingCart->totalprice = $existingCart->price * $existingCart->amount;
            $existingCart->save();
        }
        else {
            $part = Part::find($part_id);
            if (!$part || $amount <= 0) {
                return response()->json(['invalid part or amount'],400);
            }
            $cart = new Cart();
            $cart->customer_id = $customer_id;
            $cart->part_id = $part_id;
            $cart->amount = $amount;
            $cart->seller_id = $part->seller_id;
            $cart->category_id = $part->category_id;
            $cart->car_id = $part->model_id;
            $cart->price = $part->price;
            $cart->totalprice = $part->price * $amount;
            $cart->save();
        }

        return response()->json(["Added"],200);
    }else{
        return response()->json(['Your dont have enough Mony '],400);
    }
    }
    else
        return response()->json(['invalid Amount'],400);
    }
    else
        return response()->json(['You Cant Buy from Your Self'],400);

    }
    //done
    function ShowCart($id){
        $showcart=Cart::where('customer_id',$id)->with('category')->with('part')->with('seller')->with('customer')->get();
        foreach($showcart as $cart){
            $model=Car::where('id',$cart->car_id)->get('model');
            $cart->car_model=$model[0]->model;
        }
        $showcart = $showcart->filter(function ($cart) {
            if($cart->part==null){
                $deleteRow=Cart::find($cart->id);
                $deleteRow->delete();
            }
            return $cart->part !== null;
        });
        return array($showcart);
    }
    //done
    function finalPrice($id){
        $showcart=Cart::where('customer_id',$id)->get('totalprice');
        $totalCartPrice = 0;
        for ($i=0;$i<count($showcart);$i++){
            $totalCartPrice+=$showcart[$i]->totalprice;
        }
        return response()->json($totalCartPrice);
    }
    //done

    function DeleteFromCart($id){
        $cart_part=Cart::find($id);
        $delete=$cart_part->delete();
        if($delete)
            return response()->json(['Deleted'],200);
        else
            return response()->json(['Error'],404);
    }
    function DeleteAllCart($id){
        $delete = DB::delete("delete from carts  where customer_id = '".$id."'");
        if($delete)
            return response()->json(['Deleted'],200);
        else
            return response()->json(['Error'],404);
    }
}
