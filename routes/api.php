<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\authController;
use App\Http\Controllers\donationController;
use App\Http\Controllers\eventController;
use App\Http\Controllers\feedBackController;
use App\Http\Controllers\postController;
use App\Http\Controllers\testController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//test
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get("data",[testController::class,'getdate']);
// Login
Route::post("/login",[authController::class,'authUser']);
//user
Route::get("users/{id?}", [UserController::class,'usersList']);
Route::post("users",[UserController::class,'addUser']);
//volunteryType
Route::get("volunteryType/{id?}", [VolunteerController::class,'volunteerList']);
Route::post("volunteryType",[VolunteerController::class,'addvolunteer']);
Route::patch("volunteryType/{id}",[VolunteerController::class,'editvolunteer']);
Route::delete('volunteryType/{id}', [VolunteerController::class, 'deleteVolunteer']);
//admin
Route::get("admin",[AdminController::class,'adminList']);
Route::post("admin",[AdminController::class,'addAdmin']);
Route::patch("admin/{id}",[AdminController::class,'editAdmin']);
Route::delete("admin/{id}",[AdminController::class,'deleteAdmin']);
//post
Route::get("post",[postController::class,'postsList']);
Route::post("post",[postController::class,'addPost']);
Route::post("postEdit/{id}",[postController::class,'editPost']);
Route::delete('/post/{id}',[postController::class,'deletePost']);
//feedback
Route::get('feedback/{id?}',[feedBackController::class, 'feedbackList']);
Route::patch('feedback/{id}', [feedBackController::class, 'editFeedBack']);
Route::post('feedback',[feedBackController::class, 'addFeedback']);
//event
Route::get('event/{id?}',[eventController::class,"getEvent"]);
Route::post('event',[eventController::class, 'addEvent']);
Route::post('eventEdit/{id}',[eventController::class, 'editEvent']);
Route::delete('event/{id}',[eventController::class, 'deleteEvent']);
//donation
Route::get('payment/balance',[donationController::class, 'donationList']);
Route::post('payment/donation',[donationController::class, 'addDonation']);
Route::get('/send-test-email', [donationController::class, 'sendTestEmail']);


//payment
Route::post('/payment/process', [donationController::class, 'processPayment']);
Route::post('/payment/subscriptions', [donationController::class, 'processSubscription']);

