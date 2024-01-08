<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\RejectionNotification;
use Illuminate\Http\Request;
use App\Models\validationseller;
use App\Models\User;
use App\Models\user2;
use App\Models\proposecategory;
use App\Models\category;
use App\Models\CarType;
use App\Models\Car;
use App\Models\Part;
use App\Models\ProposeCarModel;
use App\Models\ProposeCarType;
use App\Models\Sale;
use App\Models\ContactUs;
use App\Models\user_backup;
use Illuminate\Support\Facades\DB;

class admin extends Controller
{
    //select * from validationsellers
    //done
    function vaildSeller(){
        $data=validationseller::all();
        return response()->json($data);
    }
    //approve seller from validationseller and insert it into Users table
    //done 2
    function approve($id){
        $data=validationseller::where('id',$id)->get();
        $preCustomer=User::where('email',$data[0]->email)->get();
        if(count($preCustomer)==0){
            $user = new User;
            $name = null;
            foreach($data as $d){
            $user->name = $d->name;
            $name = $d->name;
            $user->email = $d->email;
            $user->password = $d->password;
            $user->phone = $d->phone;
            $user->address = $d->address;
            $user->utype=$d->utype;
            }
            $saved=$user->save();
            if($saved){
                $deletefromvalidationseller = validationseller::find($id);
                $email=$deletefromvalidationseller['email'];
                $del=$deletefromvalidationseller->delete();
                $data = [
                    'subject'=>'Your acount was approved to be seller in car auto parts',
                    ];
                if($del){
                    $mail = Mail::to($email)->send(new RejectionNotification($data));
                    return response()->json(["Approved"],200);
                }
                else
                    return response()->json(['Error'],500);
            }
        }
        else{
            $update=DB::update("update users set utype = 2 where id = '".$preCustomer[0]->id."'");
            if($update){
            $row=validationseller::find($id);
            $deleteRow=$row->delete();
            //not tested
            $data = [
                'subject'=>'Your acount was approved to be seller in car auto parts',
            ];
            if($deleteRow){
                $mail = Mail::to($preCustomer[0]->email)->send(new RejectionNotification($data));
                return response()->json(["Deleted"],200);
            }
            else
                return response()->json(['Error'],500);

            }
        }

    }
//done2
    function reject($id){
        $reject=validationseller::find($id);
        $done=$reject->delete();
        if($done)
            return response()->json(['done'],200);
        else
            return response()->json(['NOT DELETED'],500);
    }
    //done
    function show_seller_function(Request $req){
        if($req->input('seller'))
            $show_sellers= User::where('utype', 2)->where('deleted_at')->where('name',$req->input('seller'))->get();
        else
            $show_sellers= User::where('utype', 2)->where('deleted_at')->get();
        return response()->json($show_sellers);
      }

    //done
    function delete_seller_function($id,$email,$subject){

        $deleted_data_find_then_delete= User::find($id);
        $delete=$deleted_data_find_then_delete->delete();

        $data = [
        'subject'=>$subject,
        ];
        if($delete){
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["Deleted"],200);
        }
        else
            return response()->json(['Error'],500);
    }
//done
    function show_baned_seller(Request $req)
    {
        if($req->input('seller'))
            $baned_seller = DB::select("select * from users where deleted_at and utype=2 and name= '".$req->input('seller')."' ");
        else
            $baned_seller = DB::select('select * from users where deleted_at and utype=2');

        return response()->json($baned_seller);
    }
//done
    function ban_seller_function2($id,$email,$subject){
        $ban_seller= user2::find($id);
        $ban=$ban_seller->delete();
        $data = [
        'subject'=>$subject,
        ];
        if($ban){
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["Banned"],200);
        }
        else
            return response()->json(['Error'],500);
}
    //done
    function unban_seller_function($id,$email,$subject){
        $unban_seller= user2::withTrashed()->where('id',$id)->where('utype',2)->restore();
        $data = [
            'subject'=>$subject,
        ];
        if($unban_seller){
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["UnBanned"],200);
        }
        else
            return response()->json(["Error"],500);
    }
    //done
    function show_customers_function(Request $req){
        if($req->input('customer'))
            $show_customers= User::where('utype', 1)->where('deleted_at')->where('name',$req->input('customer'))->get();
        else
            $show_customers= User::where('utype', 1)->where('deleted_at')->get();
        // return view('auth.dashboard',compact('show_customers'));
        return response()->json($show_customers);
    }


    //done
    function delete_customers_function($id,$email,$subject){
        $deleted_data_find_then_delete= User::find($id);
        $delete=$deleted_data_find_then_delete->delete();
        $data = [
            'subject'=>$subject,
        ];
        if($delete){
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["UnBanned"],200);
        }
        else
            return response()->json(["Error"],500);
    }
    //done
    function ban_customers_function($id,$email,$subject){
        $ban_seller= user2::find($id);
        $ban=$ban_seller->delete();
        $data = [
            'subject'=>$subject,
        ];
        if($ban){
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["UnBanned"],200);
        }
        else
            return response()->json(["Error"],500);
    }
    //done
    function show_baned_customers_function(Request $req){
        if($req->input('banedCustomer'))
            $baned_customer=DB::select("select * from users where deleted_at and utype=1 and name='".$req->input('banedCustomer')."' ");
        else
            $baned_customer=DB::select('select * from users where deleted_at and utype=1');
        // return view('auth.dashboard',compact('baned_customer'));
        return response()->json($baned_customer);
    }
//done

    function unban_customer_function($id,$email,$subject){
        $unban_seller= user2::withTrashed()->where('id',$id)->where('utype',1)->restore();
        if($unban_seller){
            $data = [
                'subject'=>$subject,
            ];
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["UnBanned"],200);
        }
        else
            return response()->json(["Error"],500);

    }

    //not done
    function ShowProposeCategory(){

        $proposecategory=proposecategory::all();
        foreach($proposecategory as $row){
            $seller_name=User::where('id',$row->seller_id)->get('name');
            $row->seller_name=$seller_name[0]->name;
        }
        return response()->json($proposecategory);
    }
    //done
    function ApproveProposeCategory($id,$seller_id,$subject){
        $data = DB::select("select name,description from proposecategories where id ='".$id."' ");
        $data2 = new category();
        $data2->name=$data[0]->name;
        $data2->description=$data[0]->description;
        $row=$data2->save();
        if($row){
        $data3=proposecategory::find($id);
        $delete=$data3->delete();
        $data = [
            'subject'=>$subject,
        ];
        if($delete){
            $emails = User::where('id',$seller_id)->get('email');
            $email=$emails[0]->email;
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["Approved"],200);
        }
        else
            return response()->json(["Error"],500);
        }
    }

//done
    function rejectProposedCategory($id,$seller_id,$subject){

        $data=proposecategory::find($id);
        $delete=$data->delete();
        $data = [
            'subject'=>$subject,
        ];
        if($delete){
            $emails = User::where('id',$seller_id)->get('email');
            $email=$emails[0]->email;
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["Rejected"],200);
        }
        else
            return response()->json(["Error"],500);
    }


//done
    function showCarTypes(Request $req){
        $carTypes=CarType::all();
        return response()->json($carTypes);
    }
//done
    function deleteCarType($id){
        $deletecartype = CarType::find($id);
        $delete=$deletecartype->delete();
        if($delete)
            return response()->json(['Deleted'],200);
        else
            return response()->json(['Error'],500);
    }
//done

    function showProposedCarMode(){
        $data=ProposeCarModel::all();
        foreach ($data as $d) {
            $seller_name=User::where('id',$d->seller_id)->get('name');
            if(count($seller_name)!=0)
                $d->seller_name=$seller_name[0]->name;
            else{
                $user_backup_seller_name=user_backup::where('user_id',$d->seller_id)->get('name');
                $d->seller_name=$user_backup_seller_name[0]->name;
            }

            $type=CarType::where('id',$d->type)->get('type');
            $d->type_name=$type[0]->type;

        }
        return response()->json($data);
    }
//done
    function ApproveProposeCar($id,$seller_id,$subject){
        $data=ProposeCarModel::where('id',$id)->get();
        $row = new Car();
        $row->model=$data[0]->model;
        $row->type_id=$data[0]->type;
        $save=$row->save();
        if($save){
            $data1 = ProposeCarModel::find($id);
            $delete=$data1->delete();

        $data = [
            'subject'=>$subject,
        ];
            if($delete){
            $emails = User::where('id',$seller_id)->get('email');
            $email=$emails[0]->email;
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["Done"],200);
        }
        else
            return response()->json(["Error"],500);
        }
    }
//done

    function RejectProposeCar($id,$seller_id,$subject){

        $data1 = ProposeCarModel::find($id);
        $delete=$data1->delete();
        $data = [
            'subject'=>$subject,
        ];
        if($delete){
            $emails = User::where('id',$seller_id)->get('email');
            $email=$emails[0]->email;
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["Done"],200);
        }
        else
            return response()->json(["Error"],500);


    }
//done
    function showCars(){
        $data=Car::all();
        foreach($data as $d){
            $type = DB::select("select type from car_types where id= '".$d->type_id."' ");
            $d->type_id = $type[0]->type;
        }
        return response()->json($data);
    }
    //done
    function deleteCar($id){
        $data=Car::find($id);
        $delete=$data->delete();
        if($delete)
            return response()->json(['Deleted'],200);
        else
            return response()->json(['Error'],500);
    }
//done
    function showProposedCarType(){
        $data=ProposeCarType::all();
        foreach($data as $d){
            $name = DB::select("select name from users where id= '".$d->seller_id."' ");
            if(count($name)!=0)
                $d->seller_name = $name[0]->name;
            else{
                $user_backup=user_backup::where('user_id',$d->seller_id)->get();
                $d->seller_name = "Deleted".$user_backup[0]->name;
            }
        }
        return response()->json($data);
    }
    //done
    function ApproveProposeCarType($id,$subject){
        $data=ProposeCarType::where('id',$id)->get();
        $row = new CarType();
        $row->type=$data[0]->type;

        $saved=$row->save();

        if($saved){
            $emails = User::where('id',$data[0]->seller_id)->get('email');
            $data1 = ProposeCarType::find($id);
            $delete=$data1->delete();

            $data = [
                'subject'=>$subject,
            ];
            if($delete){
                $email=$emails[0]->email;
                $mail = Mail::to($email)->send(new RejectionNotification($data));
                return response()->json(["Done"],200);
            }
            else
                return response()->json(["Error"],500);

        }
    }
//done
    function RejectProposeCarType($id,$subject){
        $data = ProposeCarType::find($id);
        $delete=$data->delete();

        $emails = User::where('id',$data['seller_id'])->get('email');
        $data = [
            'subject'=>$subject,
        ];
        if($delete){
            $email=$emails[0]->email;
            $mail = Mail::to($email)->send(new RejectionNotification($data));
            return response()->json(["Done"],200);
        }
        else
            return response()->json(["Error"],500);

    }

//done
    function showParts(){

        $data=Part::all();
        foreach($data as $d){
            $name = DB::select("select name from users where id='".$d->seller_id."' and deleted_at is null ");
            if(count($name)!=0)
                $d->seller_name = $name[0]->name;
            else
                $d->seller_name =$d->seller_id;
            $model = DB::select("select model from cars where id='".$d->model_id."' ");
            if(count($model)!=0)
                $d->model = $model[0]->model;
            else
                $d->model =$d->id;
            $category = DB::select("select name from categories where id= '".$d->category_id."' ");
            if(count($category)!=0)
                $d->category_name = $category[0]->name;
            else
                $d->category_name= $d->category_id  ;

        }
        return response()->json($data);
    }

//done
    function showSales(){
        $data=Sale::all();
        foreach($data as $d){
            $Customername = DB::select("select name from users where id='".$d->customer_id."' ");
            if(count($Customername)!=0)
                $d->customer_name = $Customername[0]->name;
            else
                $d->customer_name =$d->customer_id;

            $Sellername = DB::select("select name from users where id='".$d->seller_id."' ");
            if(count($Sellername)!=0)
                $d->seller_name = $Sellername[0]->name;
            else
                $d->seller_name = $d->seller_id;

            $model = DB::select("select model from cars where id= '".$d->car_id."' ");
            if(count($model)!=0)
                $d->car_model = $model[0]->model;
            else
                $d->car_model = $d->car_id;

            $category = DB::select("select name from categories where id= '".$d->category_id."'  ");
            if(count($model)!=0)
                $d->category_name = $category[0]->name;
            else
                $d->category_name = $d->category_id;


            $part = DB::select("select name from parts where id ='".$d->part_id."'  ");
            if(count($part)!=0)
                $d->part_name = $part[0]->name;
            else
                $d->part_name =$d->part_id;
        }
        return response()->json($data);
    }
//done
    function addCarType(Request $req){
        $row= new CarType();
        $row->type= $req->input('type');
        $saved=$row->save();
        if($saved)
            return response()->json(['Saved'],200);
        else
            return response()->json(['Error'],500);
    }

//done
    function SaveContactUs(Request $req){
        $row =new ContactUs();
        $row->name=$req->input('name');
        $row->email=$req->input('email');
        $row->message=$req->input('message');
        $saved=$row->save();
        if($saved)
            return response()->json(['Saved'],200);
        else
            return response()->json(['Error'],500);

    }
//done
    function showMessages(){
        return response()->json(ContactUs::all());
    }
//done
    function deleteMessage($id){
        $deleteRow=ContactUs::find($id);
        $delete=$deleteRow->delete();
        if($delete)
            return response()->json(['Saved'],200);
        else
            return response()->json(['Error'],500);
    }
//done
    function AddCarModel(Request $req){
        $data = Car::create($req->all());
        if($data)
            return response()->json(['Added'],200);
        else
            return response()->json(['Error'],500);

    }
//done
    function AddAdmin(Request $req){
        $securityCode="abohamza2001@AddAdmin";
        if( $securityCode == $req->input('securityCode') ){
            $admin=DB::update(" update users set utype = 0 where id = '".$req->input('id')."' ");
            $user=User::where('id',$req->input('id'))->get('email');
            $user_backup=user_backup::where('email',$user[0]->email)->update(['utype'=>0]);
            return response()->json(['Done'],200);
        }
        else{
            return response()->json(['security code is false'],500);
        }
    }

    function RemoveAdmin(Request $req){
        $securityCode="abohamza2001@AddAdmin";
        if( $securityCode == $req->input('securityCode') ){
            $admin=DB::update(" update users set utype = 1 where id = '".$req->input('id')."' ");
            $user=User::where('id',$req->input('id'))->get('email');
            $user_backup=user_backup::where('email',$user[0]->email)->update(['utype'=>1]);
            return response()->json(['Done'],200);
        }
        else{
            return response()->json(['security code is false'],500);
        }
    }

    function showUsersBackup(){
        return response()->json(user_backup::all());
    }
    function deleteUserBackup($id){
        $row=user_backup::where('id',$id)->delete();
        if($row)
            return response()->json(['Deleted'],200);
        else
            return response()->json(['Error'],500);
    }
}

