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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


   
   //properties
   Route::apiResource('property','PropertiesController'); 

   //Ceritificates
   Route::apiResource('certificate','CertificatesController');

  //  get Certificates of a property
   Route::get('property/{id}/certificate','PropertiesController@getCertificates');

   //get Notes of a property
   Route::get('property/{id}/note','PropertiesController@propertyNotes');

   //store Notes of a property
   Route::post('property/{id}/note','PropertiesController@storeNoteProperty');


   //get Notes of a certificate
   Route::get('certificate/{id}/note','CertificatesController@certificateNotes');



   // get properties which has more than 5 certificates
   Route::get('getproperties','PropertiesController@getProperties' );




