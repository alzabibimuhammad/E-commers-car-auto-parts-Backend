<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});
Route::get('register', function () {
    return view('register');
});

Route::get('home', function () {
    return view('home');
});


Route::post('add-user', [Users::class, 'store'])->name('add-user');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('vaildsellers', [admin::class, 'vaildseller'])->name('vaildseller');

Route::get('approved', [admin::class, 'approve'])->name('approve.seller');
Route::get('rejected', [admin::class, 'reject'])->name('reject.seller');


Route::get('delete_seller', [admin::class, 'delete_seller_function'])->name('delete.seller');
Route::get('baned_seller', [admin::class, 'ban_seller_function2'])->name('ban.seller');
Route::get('unban_seller', [admin::class, 'unban_seller_function'])->name('unban.seller');
Route::get('customers', [admin::class, 'show_customers_function'])->name('show.customer');
Route::get('delete_customer', [admin::class, 'delete_customers_function'])->name('delete.customer');
Route::get('ban_customer', [admin::class, 'ban_customers_function'])->name('ban.customer');
Route::get('show_baned_customer', [admin::class, 'show_baned_customers_function'])->name('show.baned.customers');
Route::get('unban_customer', [admin::class, 'unban_customer_function'])->name('unban.customer');

Route::get('addcartype', function(){
    return view('add_car_type');
})->name('add.car.type');


Route::get('savecartype', [admin::class, 'saveCarType'])->name('save.car.type');
Route::get('showcartype', [admin::class, 'showCarTypes'])->name('show.car.type');
Route::get('deleteCarType', [admin::class, 'deleteCarType'])->name('delete.car.type');




Route::get('category',[CategoryController::class, 'show_category'])->name('show.category');

Route::get('delete_categorys', [CategoryController::class, 'delete_category'])->name('delete.category');
Route::get('undelete_categorys', [CategoryController::class, 'undelete_category'])->name('undelete.category');
Route::get('add_category', [CategoryController::class, 'add_category'])->name('add.category');
Route::get('addedcaty', [CategoryController::class, 'save_added_category'])->name('save.category');
Route::get('edit_category', [CategoryController::class, 'edit_category'])->name('edit.category');
Route::get('update_category', [CategoryController::class, 'update_category'])->name('update.category');

Route::get('ban', [admin::class, 'show_baned_seller'])->name('show.baned.seller');

//parts
Route::get('addpart', function(){
    return view('addpart');
});
Route::get('savepart', [PartsController::class, 'savePart'])->name('save.part');
Route::get('showpart', [PartsController::class, 'showPart'])->name('show.part');
Route::get('deletepart', [PartsController::class, 'deletePart'])->name('delete.part');
Route::get('showdeletepart', [PartsController::class, 'showDeletedPart'])->name('show.delete.part');
Route::get('undeletepart', [PartsController::class, 'unDeletedPart'])->name('undelete.part');
Route::get('editpart', [PartsController::class, 'editPart'])->name('edit.part');
Route::get('saveeditpart', [PartsController::class, 'SaveEditPart'])->name('save.edit.part');

//propose category
Route::get('proposecategory', [CategoryController::class, 'ProposeCategory'])->name('propose.category');
Route::get('saveproposecategory', [CategoryController::class, 'SaveProposeCategory'])->name('save.propose.category');
Route::get('showproposecategory', [admin::class, 'ShowProposeCategory'])->name('show.propose.category');

Route::get('approvecategory', [admin::class, 'ApproveProposeCategory'])->name('approve.category');




//edit.profile.customer
Route::get('editprofile', [Users::class, 'editProfileCustomerSeller'])->name('edit.profile.customer.seller');
Route::get('updateprofile', [Users::class, 'updateProfile'])->name('update.profile');
Route::get('deleteProfileCustomerSeller', [Users::class, 'deleteProfileCustomerSeller'])->name('delete.profile.customer.seller');



//show parts customer
Route::get('showparts', [PartsController::class, 'showParts'])->name('show.parts');

//add to cart
Route::get('AddToCart', [CartController::class, 'AddToCart'])->name('add.to.cart');
// ShowCart
Route::get('ShowCart', [CartController::class, 'ShowCart'])->name('show.cart');

//search.parts
Route::get('SearchPart', [PartsController::class, 'SearchPart'])->name('search.parts');


// delete.from.cart
Route::get('deletefromcart', [CartController::class, 'DeleteFromCart'])->name('delete.from.cart');
//buy cart
Route::get('buycart', [SaleController::class, 'buy'])->name('buy.cart');





//sales
Route::get('showSales', [SaleController::class, 'showSalesForCertainSeller'])->name('show.sales.seller');



Route::get('car_model',function(){
    $car_types=DB::select('select type from car_types');
    return view('propose_car_model',compact('car_types'));
})->name('propose.car.model');

Route::get('car_type',function(){
    return view('propose_car_type');
})->name('propose.car.type');

Route::get('recieveCarModel', [CarController::class, 'recieveCarModel'])->name('send.car.model');

Route::get('sendCarType', [CarController::class, 'sendCarType'])->name('send.car.type');




Route::get('showSalesForCustomer', [SaleController::class, 'showSalesForCustomer'])->name('show.sales.customer');


