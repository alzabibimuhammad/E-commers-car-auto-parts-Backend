<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProposeCarModel;
use App\Models\ProposeCarType;
use App\Models\Car;
use App\Models\CarType;

class CarController extends Controller
{
    //done
    function Models(){
        return response()->json(Car::all('id','model'));
    }
    //done
    function Brands(){
        return response()->json(CarType::all('id','type'));
    }
    //done
    function recieveCarModel(Request $req){
        $row= new ProposeCarModel();
        $row->seller_id=$req->input('seller_id');
        $row->model=$req->input('model');
        $row->type=$req->input('type');
        $row->save();
        if($row){
            return response()->json('Added');
        }
        else
            return response()->json('Error');
    }
    //done
    function sendCarType(Request $req){
        $row=new ProposeCarType();
        $row->seller_id=$req->input('seller_id');
        $row->type=$req->input('type');
        $row->save();
        if($row)
            return response()->json("Added");
        else
            return response()->json("Error");
    }

}
