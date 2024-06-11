<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\donationController;
use App\Http\Controllers\eventController;
use App\Http\Controllers\feedBackController;
use App\Http\Controllers\postController;
use App\Http\Controllers\testApi;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VolunteerController;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get("data",[testApi::class,'getdate']);
Route::get("users/{id?}", [UserController::class,'usersList']);
Route::post("users",[UserController::class,'addUser']);
Route::get("volunteer/{id?}", [VolunteerController::class,'volunteerList']);
Route::post("volunteer",[VolunteerController::class,'addvolunteer']);
Route::patch("volunteer/{id}",[VolunteerController::class,'editvolunteer']);
Route::delete('volunteer/{id}', [VolunteerController::class, 'deleteVolunteer']);
Route::get("admin",[AdminController::class,'adminList']);
Route::post("admin",[AdminController::class,'addAdmin']);
Route::patch("admin/{id}",[AdminController::class,'editAdmin']);
Route::delete("admin/{id}",[AdminController::class,'deleteAdmin']);
Route::get("post",[postController::class,'postsList']);
Route::post("post",[postController::class,'addPost']);
Route::post("postEdit",[postController::class,'editPost']);
Route::delete('/post/{id}',[postController::class,'deletePost']);
Route::get('feedback/{id?}',[feedBackController::class, 'feedbackList']);
Route::patch('feedback/{id}', [feedBackController::class, 'editFeedBack']);
Route::post('feedback',[feedBackController::class, 'addFeedback']);
Route::get('event/{id?}',[eventController::class,"getEvent"]);
Route::post('event',[eventController::class, 'addEvent']);
Route::post('eventEdit/{id}',[eventController::class, 'editEvent']);
Route::delete('event/{id}',[eventController::class, 'deleteEvent']);
Route::get('donation/{id?}',[donationController::class, 'donationList']);
Route::post('donation',[donationController::class, 'addDonation']);



