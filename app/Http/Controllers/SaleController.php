<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Sale;
use App\Models\User;
use App\Models\Part;
use App\Models\Car;
use App\Models\category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RejectionNotification;
use App\Models\user_backup;

use time;
class SaleController extends Controller
{
    //done
    public function buy(Request $req){
        $customer_id=$req->input('id');
        $customer_financial_balance=User::where('id',$customer_id)->get();
        $finalPriceForAllparts=0;
        $carts=Cart::all()->where('customer_id',$customer_id);
        $checkMonyBeforeBuying=0;
        //checkMonyBeforeBuying
        foreach ($carts as $cart){
            $checkMonyBeforeBuying+=$cart->totalprice;
        }
        if($checkMonyBeforeBuying<=$customer_financial_balance[0]->financial_balance){
        if(count($carts)!=0){
        foreach($carts as $cart){
            $ActualAmount=Part::where('id',$cart->part_id)->get('amount');
            if($ActualAmount[0]->amount >= $cart->amount & $cart->totalprice <= $customer_financial_balance[0]->financial_balance ){
                $sale= new Sale();
                $sale->customer_id=$customer_id;
                $sale->seller_id=$cart->seller_id;
                $sale->car_id=$cart->car_id;
                $sale->part_id=$cart->part_id;
                $sale->category_id=$cart->category_id;
                $sale->price=$cart->price;
                $sale->amount=$cart->amount;
                $sale->totalprice=$cart->totalprice;
                //save sale
                $sale->save();
                //update amount
                $update_actuall_amount=DB::update("update parts set amount = '".$ActualAmount[0]->amount-$cart->amount."' where id ='".$cart->part_id."' ");
                //update balance
                $update_user_balance=DB::update("update users set financial_balance = '".$customer_financial_balance[0]->financial_balance-$cart->totalprice."' where id ='".$customer_id."' ");
                //update profits
                $preProfits=User::where('id',$cart->seller_id)->get();
                $update_seller_profits=DB::update("update users set profits = '".$preProfits[0]->profits+$cart->totalprice."' where id ='".$cart->seller_id."' ");
                //update seller user backup
                $seller_user_backUp=user_backup::where('email',$preProfits[0]->email)->get();
                $update_seller_user_backUp=user_backup::where('id',$seller_user_backUp[0]->id)->update(array('profits'=>$seller_user_backUp[0]->profits+$cart->totalprice));
                //update customer user backup
                $customer_user_backup=user_backup::where('email',$customer_financial_balance[0]->email)->get();
                $update_customer_user_backup=user_backup::where('id',$customer_user_backup[0]->id)->update(array('financial_balance'=>$customer_financial_balance[0]->financial_balance-$cart->totalprice));
                //final Price For All parts
                $finalPriceForAllparts+=$cart->totalprice;

                //Mails for sellers
                $data1 = [
                    'subject'=>"The auto part ID: ('".$cart->part_id."') has been purchased and the price of ('".$cart->totalprice."') has been added to your profites",
                ];
                $mailForCustomer = Mail::to($preProfits[0]->email)->send(new RejectionNotification($data1));

                //deleted cart
                $delete_cart = Cart::find($cart->id);
                $delete_cart->delete();
            }
            else
                return response()->json(["Balance is not good"],500);
            }

            $data = [
                'subject'=>"Purchase successfully completed for an price of '".$finalPriceForAllparts."' ",
            ];
            $mailForCustomer = Mail::to($customer_financial_balance[0]->email)->send(new RejectionNotification($data));

            return response()->json("The Order is Done");
        }

        else
            return response()->json("Cart is empty");
        }
        else
            return response()->json(['you dont have enouph mony '],400);

        }

        //done
    function showPrushesForCustomerAndSeller($id){
        $sales = Sale::where('customer_id',$id)->get();
        foreach($sales as $sale){
            //seller Name
            $sellerName=User::where('id',$sale->seller_id)->get();
            if(count($sellerName)!=0){
                $sale->seller_name=$sellerName[0]->name;
                $sale->seller_email=$sellerName[0]->email;
            }
            else{
                //not tested
                $seller_backup=user_backup::where('user_id',$sale->seller_id)->get();
                $sale->seller_name=$seller_backup[0]->name."Deleted";
                $sale->seller_email=$seller_backup[0]->email."Deleted";
            }
            //category Name
            $categoryName=category::where('id',$sale->category_id)->get('name');
            if(count($categoryName)!=0)
                $sale->category_name=$categoryName[0]->name;
            else
                $sale->category_name="Deleted".$sale->category_id;

            //Model
            $model = Car::where('id',$sale->car_id)->get('model');
            if(count($model)!=0)
                $sale->model=$model[0]->model;
            else
                $sale->model=$sale->model_id."Deleted";
            //part Name
            $partName=Part::where('id',$sale->part_id)->get('name');
            if(count($partName)!=0)
                $sale->part_name=$partName[0]->name;
            else
                $sale->part_name=$sale->part_id."Deleted";
            //customer Name
            $customerName=User::where('id',$id)->get('name');
            $sale->customer_name=$customerName[0]->name;
        }
        return response()->json($sales);
    }

    //done
    function showSalesForCertainSeller($id){
        $data = Sale::where('seller_id',$id)->get();
        foreach($data as $dat){
            //part name
            $part_name=Part::where('id',$dat->part_id)->get('name');
            if(count($part_name)!=0)
                $dat->part_name=$part_name[0]->name;
            else
                $dat->part_name="Deleted".$dat->part_id;
            //model
            $model=Car::where('id',$dat->car_id)->get('model');
            if(count($model)!=0)
                $dat->model=$model[0]->model;
            else
                $dat->model="Deleted".$dat->car_id;
            //category name
            $category_name=category::where('id',$dat->category_id)->get('name');
            if(count($category_name)!=0)
                $dat->category_name=$category_name[0]->name;
            else
                $dat->category_name="Deleted".$dat->category_id;
            //customer name
            $customer_name=User::where('id',$dat->customer_id)->get();
            if(count($customer_name)!=0){
                $dat->customer_name=$customer_name[0]->name;
                $dat->customer_email=$customer_name[0]->email;
            }
            else{
                $user_backup=user_backup::where('user_id', $dat->customer_id)->get();
                $dat->customer_name="Deleted.".$user_backup[0]->name;
                $dat->customer_email="Deleted.".$user_backup[0]->email;
                // return $user_backup;
            }
        }
        return response()->json($data);
    }
    //show total sales mony
    //done
    function totalSalesMony($id){
        $price=Sale::where('seller_id',$id)->get('totalprice');
        $totalSalesMony = 0;
        for ($i=0;$i<count($price);$i++){
            $totalSalesMony+=$price [$i]->totalprice;
        }
        return response()->json($totalSalesMony);
    }

    //show total mony for customer prushes

    //done
    function finalPrice($id){
        $showcart=Sale::where('customer_id',$id)->get('totalprice');
        $totalCartPrice = 0;
        for ($i=0;$i<count($showcart);$i++){
            $totalCartPrice+=$showcart[$i]->totalprice;
        }
        return response()->json($totalCartPrice);
    }

}
