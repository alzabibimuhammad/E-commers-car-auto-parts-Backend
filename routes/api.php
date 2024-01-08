<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Users;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Auth2;
use App\Models\user_backup;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use GuzzleHttp\Client;


use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/', function(){
    return "kmdf";
});

// Auth::routes(['verify'=>true]);

Route::post('post-registration', [AuthController::class, 'postRegistration']);
Route::post('login', [AuthController::class, 'login'])  ;

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout',[AuthController::class, 'logout']);
});

Route::get('showProfile/{id}', [Users::class, 'showProfile']);





//show sellers
Route::get('sellers', [admin::class, 'show_seller_function'])->name('show.seller');


//show customer
Route::get('customers', [admin::class, 'show_customers_function'])->name('show.customer');


//show banned customer
Route::get('show_baned_customer', [admin::class, 'show_baned_customers_function'])->name('show.baned.customers');






//show banned seller
Route::get('ban', [admin::class, 'show_baned_seller'])->name('show.baned.seller');


//ban seller
Route::get('baned_seller/{id}/{email}/{subject}', [admin::class, 'ban_seller_function2'])->name('ban.seller');


//unban seller
Route::get('unban_seller/{id}/{email}/{subject}', [admin::class, 'unban_seller_function'])->name('unban.seller');



//delete customer
Route::get('delete_customer/{id}/{email}/{subject}', [admin::class, 'delete_customers_function']);
//ban customer
Route::get('ban_customer/{id}/{email}/{subject}', [admin::class, 'ban_customers_function']);


//unban customer
Route::get('unban_customer/{id}/{email}/{subject}', [admin::class, 'unban_customer_function']);




    //save Category
    Route::post('addedcaty', [CategoryController::class, 'save_added_category'])->name('save.category');

    //delete category
    Route::get('delete_categorys/{id}', [CategoryController::class, 'delete_category'])->name('delete.category');

    //show deleted categories
    Route::get('showDeletedCategories', [CategoryController::class, 'show_deleted_categories']);

    //undelelte category
    Route::get('undelete_categorys/{id}', [CategoryController::class, 'undelete_category'])->name('undelete.category');



    Route::get('delete_customer/{id}', [admin::class, 'delete_customers_function'])->name('delete.customer');


    Route::get('delete_seller/{id}/{email}/{subject}', [admin::class, 'delete_seller_function'])->name('delete.seller');






    Route::get('vaildsellers', [admin::class, 'vaildseller']);



    //approve request seller
    Route::get  ('approved/{id}', [admin::class, 'approve']);


    // reject request seller
    Route::get('rejected/{id}', [admin::class, 'reject'])->name('reject.seller');

    //register




    //pie charts
    Route::get('pie', function(){
        $data=DB::select('select count(id) as customers from users where utype = 1 and deleted_at is null ');
        $data1=DB::select('select count(id) as sellers from users where utype = 2 and deleted_at is null ');
        $data2=DB::select('select count(id) as bannedCustomer from users where utype = 1 and deleted_at is not null ');
        $data3=DB::select('select count(id) as bannedSellers from users where utype = 2 and deleted_at is not null ');
        $finaldata = [
            ["id" => 'customer', 'value' => $data[0]->customers],
            ["id" => 'seller', 'value' => $data1[0]->sellers],
            ["id" => 'bannedCustomer', 'value' => $data2[0]->bannedCustomer],
            ["id" => 'bannedSeller', 'value' => $data3[0]->bannedSellers],
        ];
        $json = json_encode($finaldata);
        return response($json);
    })->name('login.post');
    //
    //end pie charts


    Route::get('getUser', [AuthController::class, 'data']);




    Route::get('count',function(){
        $validationsellers=DB::select("select count(id) as validationsellers from validationsellers");
        $proposedCategory = DB::select("select count(id) as proposedcategory from proposecategories");
        $proposedCars = DB::select("select count(id) as proposedcars from propose_car_models");
        $proposedCarTypes = DB::select("select count(id) as proposedcartypes from propose_car_types");
        $hiddenCategories = DB::select("select count(id) as hiddenCategories from categories where deleted_at is not null ");
        $bannedSeller = DB::select("select count(id) as bannedSeller from users where utype=2 and deleted_at is not null ");
        $bannedCustomer = DB::select("select count(id) as bannedCustomer from users where utype=1 and deleted_at is not null ");
        $messages = DB::select("select count(id) as messages from contact_us");
        $finaldata = [
            ['validationsellers' => $validationsellers[0]->validationsellers],
            ['proposedcategory' => $proposedCategory[0]->proposedcategory],
            ['proposedcars' => $proposedCars[0]->proposedcars],
            ['proposedcartypes' => $proposedCarTypes[0]->proposedcartypes],
            ['hiddenCategories' => $hiddenCategories[0]->hiddenCategories],
            ['bannedSeller' => $bannedSeller[0]->bannedSeller],
            ['bannedCustomer' => $bannedCustomer[0]->bannedCustomer],
            ['messages' => $messages[0]->messages],
        ];
        return response()->json($finaldata);
    });


    //show propose category
    Route::get('showproposecategory', [admin::class, 'ShowProposeCategory'])->name('show.propose.category');

    //approve propose category
    Route::get('approvecategory/{id}/{seller_id}/{subject}', [admin::class, 'ApproveProposeCategory'])->name('approve.category');
    //reject propose category
    Route::get('rejectproposedcategory/{id}/{seller_id}/{subject}', [admin::class, 'rejectProposedCategory']);


    //show proposed car model
    Route::get('showproposedCarMode', [admin::class, 'showProposedCarMode']);

    //approve propsed car
    Route::get('ApproveProposeCar/{id}/{seller_id}/{subject}', [admin::class, 'ApproveProposeCar']);

    //reject proposed car
    Route::get('RejectProposeCar/{id}/{seller_id}/{subject}', [admin::class, 'RejectProposeCar']);


    // showCars
    Route::get('showCars', [admin::class, 'showCars']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");


    //delete car
    Route::get('deleteCar/{id}', [admin::class, 'deleteCar']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");

    //delete car typw
    Route::get('deleteCarType/{id}', [admin::class, 'deleteCarType']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");


    //show proposed car type
    Route::get('showProposedCarType', [admin::class, 'showProposedCarType']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");

    // Approve Propose Car Type
    Route::get('ApproveProposeCarType/{id}/{subject}', [admin::class, 'ApproveProposeCarType']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");

    //reject Propose Car Type
    Route::get('RejectProposeCarType/{id}/{subject}', [admin::class, 'RejectProposeCarType']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");

    //show cat type
    Route::get('showcartype', [admin::class, 'showCarTypes']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");
    //show parts
    Route::get('showParts', [admin::class, 'showParts']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");

    //show sales
    Route::get('showSales', [admin::class, 'showSales']);
    // ->withoutMiddleware("throttle:api")->middleware("throttle:300:1");
    Route::get('recentTransactions', function(){
        $data = DB::select("select id,customer_id,created_at,totalprice from sales
        order by created_at desc limit 10 ");
        foreach ($data as $d){
            $name=DB::select("select name from users where id = '".$d->customer_id."' ");
            if(count($name)!=0)
                $d->customer_name = $name[0]->name;
            else
                $d->customer_name="Deleted";
            }
        return response()->json($data);
    });


//add car type
Route::post('addCarType', [admin::class, 'addCarType']);
//add car model
Route::get('AddCarModel', [admin::class, 'AddCarModel']);
Route::post('SaveContactUs',[admin::class, 'SaveContactUs']);
Route::get('showMessages',[admin::class, 'showMessages']);
Route::get('deleteMessage/{id}',[admin::class, 'deleteMessage']);

Route::post('AddAdmin',[admin::class, 'AddAdmin']);


Route::post('RemoveAdmin',[admin::class, 'RemoveAdmin']);


Route::get('showUsersBackup',[admin::class, 'showUsersBackup']);
Route::get('deleteUserBackup/{id}',[admin::class, 'deleteUserBackup']);


//home page form car type
Route::get('carType',function(){
    $data = DB::select('select id,type from car_types ');
    return response()->json($data);

});
//home page form car model for certain type
Route::get('carModel',function(Request $request){
    $data = DB::select(" select id,model from cars where type_id='".$request->input('type')."' ");
    return response()->json($data);

});

//home page form categories
Route::get('partCategories',function(){
    $data = DB::select("select id,name from categories where deleted_at is null");
    return response()->json($data);
});

//home page form Part for certain mode
Route::get('carParts',[PartsController::class , 'carParts']);





//test

// Route::get('buy', [SaleController::class, 'buy']);


Route::get('deleteProfileCustomerSellerAPI/{id}', [Users::class, 'deleteProfile']);

Route::post('updateProfile', [Users::class, 'updateProfile']);

//MoveToBalance

Route::get('MoveToBalance/{id}/{mony}', [Users::class, 'MoveToBalance']);


//parts customer
Route::get('showparts', [PartsController::class, 'showParts']);


//add to cart
Route::get('AddToCart', [CartController::class, 'AddToCart']);
//show cart
Route::get('ShowCart/{id}', [CartController::class, 'ShowCart'])->name('show.cart');

//delete from cart
Route::get('deletefromcart/{id}', [CartController::class, 'DeleteFromCart']);

//delete all cart
Route::get('DeleteAllCart/{id}', [CartController::class, 'DeleteAllCart']);



//buy cart

Route::get('buycart', [SaleController::class, 'buy']);
//final price

Route::get('finalPrice/{id}', [CartController::class, 'finalPrice']);


//part Details

Route::get('PartDetails/{id}', [PartsController::class, 'PartDetails']);


//show sales for certain customer
Route::get('showPrushesForCustomerAndSeller/{id}', [SaleController::class, 'showPrushesForCustomerAndSeller']);


//models
Route::get('Models', [CarController::class, 'Models']);

//Brands
Route::get('Brands', [CarController::class, 'Brands']);

//category
Route::get('category', [CategoryController::class, 'show_category'])->name('show.category');

//save part
Route::post('savepart', [PartsController::class, 'savePart']);



//show parts for certain seller
Route::get('showPartSeller/{id}', [PartsController::class, 'showPartSeller']);

//show deleted part
Route::get('showDeletedPart/{id}', [PartsController::class, 'showDeletedPart']);


//delete part
Route::get('deletePart/{id}', [PartsController::class, 'deletePart']);


//undelete part
Route::get('unDeletedPart/{id}', [PartsController::class, 'unDeletedPart']);


Route::get('DeleteAllParts/{id}', [PartsController::class, 'DeleteAllParts']);
Route::get('UnDeleteAllParts/{id}', [PartsController::class, 'UnDeleteAllParts']);



//total mony in the system

Route::get('totalMony/{id}', [SaleController::class, 'finalPrice']);

//propose category
Route::get('saveproposecategory', [CategoryController::class, 'SaveProposeCategory']);

//propose car model
Route::get('recieveCarModel', [CarController::class, 'recieveCarModel']);

//propose car type
Route::get('sendCarType', [CarController::class, 'sendCarType']);


//show Sales For Certain Seller
Route::get('showSalesForCertainSeller/{id}', [SaleController::class, 'showSalesForCertainSeller']);


// totalSalesMony
Route::get('totalSalesMony/{id}', [SaleController::class, 'totalSalesMony']);






//ReportController

Route::get('showReport', [ReportController::class, 'showReport']);


// seller_part_name
Route::get('sellerPartName/{seller_id}/{part_id}', [ReportController::class, 'seller_part_name']);

// Addreport
Route::get('Addreport', [ReportController::class, 'Addreport']);

//DeletePartFromReport
Route::get('DeletePartFromReport/{id}', [ReportController::class, 'DeletePartFromReport']);


//delete Report
Route::get('DeleteReport/{id}', [ReportController::class, 'DeleteReport']);


//edit category
Route::get('edit_category/{id}', [CategoryController::class, 'edit_category']);

//update_category
Route::get('update_category', [CategoryController::class, 'update_category']);


//edit part
Route::get('editpart/{id}', [PartsController::class, 'editPart']);

//updatePart
Route::post('saveEditPart', [PartsController::class, 'SaveEditPart']);

//search part
Route::get('searchPart', [PartsController::class, 'searchPart']);
Route::get('searchPartHeader', [PartsController::class, 'searchPartHeader']);




