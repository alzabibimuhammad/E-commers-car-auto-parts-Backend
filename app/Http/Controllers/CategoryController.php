<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\Part;
use App\Models\Cart;
use App\Models\parts2;
use Illuminate\Support\Facades\DB;
use App\Models\proposecategory;
use Illuminate\Support\Facades\File;


class CategoryController extends Controller
{
    //done
    function show_category(){
        $category=category::where('deleted_at')->get();
        return response()->json($category);
    }
    //done
    function delete_category($id){
        $category = Category::find($id);
        $delete_parts = Part::where('category_id', $id)->get();
        foreach ($delete_parts as $part) {
            $delete_carts = Cart::where('part_id',$part->id)->get();

            foreach($delete_carts as $cart)
                $cart->delete();

            $filePath = public_path($part->image);
            if (File::exists($filePath))
                File::delete($filePath);

            $part->delete();
        }
        $delete=$category->delete();
        if($delete)
            return response()->json(["Deleted"],200);
        else
            return response()->json(["Error"],500);

    }

    //done
    function show_deleted_categories(){
        $deleted_categories=DB::select('select * from categories where deleted_at is not null');
        return response()->json($deleted_categories);
    }
    //done
    function undelete_category($id){
        $undelete_category= category::withTrashed()->where('id',$id)->restore();

        if($undelete_category)
            return response()->json("Undeletd");
        else
            return response()->json("Error");


    }




    function save_added_category(Request $req){
        $data = $req->all();
        $create = category::create($data);
        if($create)
            return response()->json("Added");
        else
            return response()->json("Error");

    }

    function edit_category($id){
        $data_category=category::where('id',$id)->get();
        return response()->json($data_category);
    }

    function update_category(Request $req){
        $update_part=category::where('id', $req->input('id'))->update($req->all());
        if($update_part)
            return response()->json("updated");
        else
            return response()->json("Error");


    }

    // function update_category(Request $req){
    //     $update_part=category::where('id', '=', $req->input('id'))->update($req->except(['_method','_token']));
    //     return redirect('dashboard');
    // }
    function ProposeCategory(){
        return view('add_category');
    }
    //done
    function SaveProposeCategory(Request $req){
        $req->validate([
            'name' => 'required',
            'description'=>'required',
            'seller_id'=>'required'
        ]);
        $data=$req->all();
        $data2=proposecategory::create($data);
        $save=$data2->save();
        if($save)
            return response()->json("Added");
        else
            return response()->json("Error");

    }




}
