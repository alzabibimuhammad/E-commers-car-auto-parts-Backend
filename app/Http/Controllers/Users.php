<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\user2;
use App\Models\user_backup;
use Hash;
use App\Models\Cart;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\DB;
class Users extends Controller
{
    function updateProfile(Request $req){
        $data = $req->all();
        if($req->input('password') != 'undefined'){
            $data['password']=Hash::make($req->input('password'));
        }
        else{
            $data= $req->except(['password']);
        }
        $image = $req->file('image');
        $profile = User::where('id',$data['id'])->get();

        if ($image && $image->isValid()) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $path=$image->move(public_path('users'), $imageName);

            $filePath = public_path($profile[0]->image);
            if (File::exists($filePath))
                File::delete($filePath);
            $data['image']='users/'.$imageName;
        }
        unset($data['id']);
        $update_user_backup=user_backup::where('email',$profile[0]->email)->update($data);
        $update=User::where('id', $req->input('id'))->update($data);
        if($update)
            return response()->json("Updated");
        else
            return response()->json("Error");
    }

    //done
    function deleteProfile($id){
        $profile=User::find($id);
        $user_backup=user_backup::where('email',$profile['email'])->update(array('user_id'=>$id));
        //constrains
        $deleteCartForconstrains=DB::delete("delete from carts where customer_id ='".$id."' ");
        $filePath = public_path($profile['image']);
        if (File::exists($filePath))
            File::delete($filePath);
        $delete=$profile->delete();
        if($delete)
            return response()->json("Deleted");
        else
            return response()->json("Error");
        }
    //done
    function showProfile($id){
        $profile=User::where('id',$id)->get();
        return response()->json($profile);
    }

    function MoveToBalance($id,$mony){
        $user=User::where('id',$id)->get();
        if($mony<=$user[0]->profits){
            $updateUser=DB::update("update users set profits ='".$user[0]->profits-$mony."',financial_balance='".$user[0]->financial_balance+$mony."' where id='".$id."'");
            return response()->json('Done');
        }
        else{
            return response()->json(['error' => 'The number you entered is bigger than total profits'], 500);
        }
    }
}
