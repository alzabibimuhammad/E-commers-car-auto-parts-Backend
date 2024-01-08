<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\User;
use App\Models\report;


class ReportController extends Controller
{



    //not done for dashboard admin
    function showReport(){
        $data=report::with('seller')->with('customer')->with('parts')->get();
        if($data[0]->seller->deleted_at==null){
        foreach($data as $dat){
            if($dat->parts!=null){
                $dat->part_name=$dat->parts->name;
                $dat->seller_name=$dat->seller->name;
                $dat->customer_name=$dat->customer->name;
            }
            else{
            $delete_Report_If_No_Part=report::find($dat->id);
            $delete_Report_If_No_Part->delete();
            }
        }

        return response()->json($data);
    }

    }

    //for show
    //done
    function seller_part_name($id_seller,$id_part){
        $seller = User::where('id',$id_seller)->get('name');
        $part = Part::where('id',$id_part)->get('name');
        $finaldata = [
            ['seller_name' => $seller[0]->name],
            ['part_name' => $part[0]->name],
        ];
        return response()->json($finaldata);
    }

    function Addreport(Request $req){
        $row = report::create($req->all());
        return response()->json("Added");
    }

    function DeletePartFromReport($id){
        $row=Part::find($id);
        $delete=$row->delete();
        if($delete)
            return response()->json('Deleted');
        else
            return response()->json("Error");


    }

    function DeleteReport($id){
        $row=report::find($id);
        $delete=$row->delete();
        if($delete)
            return response()->json('Deleted');
        else
            return response()->json("Error");

    }

}
