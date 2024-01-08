<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\parts2;
use Illuminate\Support\Facades\DB;
use App\Models\category;
use App\Models\CarType;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\RejectionNotification;
use Illuminate\Support\Facades\File;


class PartsController extends Controller
{
    //done
    function savePart(Request $request)
    {

        $part = new Part();
        $part->name = $request->input('name');
        $part->seller_id = $request->input('seller_id');
        $part->model_id = $request->input('model_id');
        $part->category_id = $request->input('category_id');
        $part->amount = $request->input('amount');
        $part->price = $request->input('price');
        $part->name = $request->input('name');
        $part->description = $request->input('description');
        $image = $request->file('image');
        if ($image && $image->isValid()) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $path=$image->move(public_path('parts'), $imageName);
            $part->image='parts/'.$imageName;
        }

        $part->save();

        if($part)
            return response()->json("Added");
        else
            return response()->json("Error");

    }

    //done
    function showPartSeller($id){
        $showpart=Part::where('seller_id',$id)->where('deleted_at')->get();
        foreach($showpart as $part){
            $model=Car::where('id',$part->model_id)->get('model');
            $part->model=$model[0]->model;
            $category_name=category::where('id',$part->category_id)->where('deleted_at')->get('name');
            $part->category_name=$category_name[0]->name    ;
        }
        return response()->json($showpart);
    }
    //done
    function showDeletedPart($id){
        $showdeletedpart=DB::select("select * from parts where deleted_at and seller_id='".$id."' ");
        foreach($showdeletedpart as $part){
            $model=Car::where('id',$part->model_id)->get('model');
            $part->model=$model[0]->model;
            $category_name=category::where('id',$part->category_id)->get('name');
            $part->category_name=$category_name[0]->name    ;
        }
        return response()->json($showdeletedpart);
    }
    //done
    function deletePart($id){
        $id_part=parts2::find($id);
        $delete=$id_part->delete();
        if($delete)
            return response()->json("Deleted");
        else
            return response()->json("Error");
        }

    //done
    function unDeletedPart($id){
        $undeletePart= parts2::withTrashed()->where('id',$id)->restore();
        if($undeletePart)
            return response()->json("Successfully Restored");
        else
            return response()->json("Error");

    }
    //done
    function DeleteAllParts($id){
        $deleteAllParts=parts2::where('seller_id',$id)->delete();
        if($deleteAllParts)
            return response()->json(['Deleted'],200);
        else
            return response()->json(['Error'],200);
    }
    //done
    function UnDeleteAllParts($id){
        $deleteAllParts=parts2::where('seller_id',$id)->restore();
        if($deleteAllParts)
            return response()->json(['Restored'],200);
        else
            return response()->json(['Error'],200);
    }

    //done show
    function editPart($id){
        $edit_part=Part::where('id',$id)->with('category')->get();
        $model_name=Car::where('id',$edit_part[0]->model_id)->get('model');
        $edit_part[0]->model_name=$model_name[0]->model;
        return response()->json($edit_part);
    }

    //done
    function SaveEditPart(Request $req){
        $data= $req->except(['part_id']);

        $part_id=$req->input('part_id');
        $image = $req->file('image');

        $part = Part::where('id',$part_id)->get();

        if ($image && $image->isValid()) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $path=$image->move(public_path('parts'), $imageName);

            $filePath = public_path($part[0]->image);
            if (File::exists($filePath))
                File::delete($filePath);

            $data['image']='parts/'.$imageName;
        }
        $update_part=Part::where('id', $req->input('part_id'))->update($data);

        if($update_part)
            return response()->json("Updated");
        else
            return response()->json("Error");
    }
    //done
    function carParts(Request $request){
        $array = array();
        if($request->input('category')==null & $request->input('model') == null & $request->input('carType')==null ){
            $data=Part::where('deleted_at')->with('seller')->with('category')->get();
            foreach($data as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
                $model_name=Car::where('id',$dat->model_id)->get('model');
                $dat->model_id=$model_name[0]->model;
            }
            return response()->json(array($data));
        }
        elseif($request->input('category')==null & $request->input('model') == null & $request->input('carType')!=null){
            $model_id = Car::where('type_id',$request->input('carType'))->get('id');
            foreach($model_id as $id){
                $data=Part::where('deleted_at')->where('model_id',$id->id)->with('seller')->with('category')->get();
                foreach($data as $dat){
                    $type_id=Car::where('id',$dat->model_id)->get('type_id');
                    $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                    $dat->type=$type_name[0]->type;
                    $model_name=Car::where('id',$dat->model_id)->get('model');
                    $dat->model_id=$model_name[0]->model;
                }
                array_push($array,$data);
            }
            return response()->json($array);
        }
        elseif($request->input('category') == null & $request->input('model')!=null){
            $data=Part::where('deleted_at')->where('model_id',$request->input('model'))->with('seller')->with('category')->get();
            foreach($data as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
                $model_name=Car::where('id',$dat->model_id)->get('model');
                $dat->model_id=$model_name[0]->model;
            }
            return response()->json(array($data));
        }
        elseif($request->input('category') != null & $request->input('model')==null & $request->input('carType')==null){
            $data=Part::where('deleted_at')->where('category_id',$request->input('category'))->with('seller')->with('category')->get();
            foreach($data as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
                $model_name=Car::where('id',$dat->model_id)->get('model');
                $dat->model_id=$model_name[0]->model;
            }
            return response()->json(array($data));
        }
        elseif($request->input('category') != null & $request->input('model')!=null){
            $data=Part::where('deleted_at')->where('category_id',$request->input('category'))->where('model_id',$request->input('model'))->with('seller')->with('category')->get();
            foreach($data as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
                $model_name=Car::where('id',$dat->model_id)->get('model');
                $dat->model_id=$model_name[0]->model;
            }
            return response()->json(array($data));
        }
        elseif($request->input('category') != null &$request->input('carType')!=null ){
            $model_id = Car::where('type_id',$request->input('carType'))->get('id');
            foreach($model_id as $id){
                $data=Part::where('deleted_at')->where('model_id',$id->id)->where('category_id',$request->input('category'))->with('seller')->with('category')->get();
                foreach($data as $dat){
                    $type_id=Car::where('id',$dat->model_id)->get('type_id');
                    $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                    $dat->type=$type_name[0]->type;
                    $model_name=Car::where('id',$dat->model_id)->get('model');
                    $dat->model_id=$model_name[0]->model;
                }
                array_push($array,$data);
            }
            return response()->json($array);
        }
    }
    //done
    function PartDetails($id){
        $data=Part::where('id',$id)->with('seller')->with('category')->get();
        //car type
        $type_id = Car::where('id',$data[0]->model_id)->get('type_id');
        $car_type=CarType::where('id',$type_id[0]->type_id)->get();
        $data[0]->type_id=$car_type[0]->id;
        $data[0]->type=$car_type[0]->type;
        $model = Car::where('id',$data[0]->model_id)->get('model');
        $data[0]->model=$model[0]->model;
        return response()->json(array($data));
    }


    function searchPart(Request $request){
        $array = [];
        if($request->input('category')=='null' & $request->input('model') == 'null' & $request->input('carType')!='null'){
            $model_id = Car::where('type_id',$request->input('carType'))->get('id');
            foreach($model_id as $id){
                $data=Part::where('deleted_at')->where('model_id',$id->id)->with('seller')->with('category')->get();
                foreach($data as $dat){
                    $type_id=Car::where('id',$dat->model_id)->get('type_id');
                    $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                    $dat->type=$type_name[0]->type;
                    $model_name=Car::where('id',$dat->model_id)->get('model');
                    $dat->model_id=$model_name[0]->model;
                }
                array_push($array,$data);
            }
            return response()->json($array);
        }
        elseif($request->input('category') == 'null' & $request->input('model')!='null'){

            $data=Part::where('deleted_at')->where('model_id',$request->input('model'))->with('seller')->with('category')->get();
            foreach($data as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
                $model_name=Car::where('id',$dat->model_id)->get('model');
                $dat->model_id=$model_name[0]->model;
            }
            return response()->json(array($data));
        }
        elseif($request->input('category') != 'null' & $request->input('model')=='null' & $request->input('carType')=='null'){

            $data=Part::where('deleted_at')->where('category_id',$request->input('category'))->with('seller')->with('category')->get();
            foreach($data as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
                $model_name=Car::where('id',$dat->model_id)->get('model');
                $dat->model_id=$model_name[0]->model;
            }
            return response()->json(array($data));
        }
        elseif($request->input('category') != 'null' & $request->input('model')!='null'){

            $data=Part::where('deleted_at')->where('category_id',$request->input('category'))->where('model_id',$request->input('model'))->with('seller')->with('category')->get();
            foreach($data as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
                $model_name=Car::where('id',$dat->model_id)->get('model');
                $dat->model_id=$model_name[0]->model;
            }
            return response()->json(array($data));
        }
        elseif($request->input('category') != 'null' & $request->input('carType')!='null' ){
            $model_id = Car::where('type_id',$request->input('carType'))->get('id');
            foreach($model_id as $id){
                $data=Part::where('deleted_at')->where('model_id',$id->id)->where('category_id',$request->input('category'))->with('seller')->with('category')->get();
                foreach($data as $dat){
                    $type_id=Car::where('id',$dat->model_id)->get('type_id');
                    $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                    $dat->type=$type_name[0]->type;
                    $model_name=Car::where('id',$dat->model_id)->get('model');
                    $dat->model_id=$model_name[0]->model;
                }
                array_push($array,$data);
            }
            return response()->json($array);
        }
    }

    function searchPartHeader(Request $req)
    {
        $term = $req->query('term','');
        $parts=Part::search($term)->with('category')->with('seller')->with('model')->get();
        if(count($parts)!=0){
            foreach($parts as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
            }
            return response()->json($parts);
        }

        else{
            $parts=Part::where('deleted_at')->with('category')->with('seller')->with('model')->get();
            foreach($parts as $dat){
                $type_id=Car::where('id',$dat->model_id)->get('type_id');
                $type_name=CarType::where('id',$type_id[0]->type_id)->get('type');
                $dat->type=$type_name[0]->type;
            }
            return response()->json($parts);
        }

    }
}
