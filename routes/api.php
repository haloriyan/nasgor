<?php

use Illuminate\Support\Facades\Route;

require base_path('routes/api_routes.php'); 

Route::group(['prefix' => "staging", 'middleware' => "StagingDb", 'as' => "staging."], function () {
    require base_path('routes/api_routes.php'); 
});