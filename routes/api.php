<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('add-email', 'EmailSubscriptionController@AddEmail');
Route::post('unsubscribe-email', 'EmailSubscriptionController@DeleteEmail');

Route::post('add-newsletter', 'NewsletterController@AddNewsletter');
Route::post('delete-newsletter', 'NewsletterController@DeleteNewsletter');
Route::get('get-newsletter', 'NewsletterController@NewsletterList');
