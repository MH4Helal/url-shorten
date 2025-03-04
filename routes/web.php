<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UrlShortenerController;

Route::post('/encode', [UrlShortenerController::class, 'encode']);
Route::get('/decode/{shortCode}', [UrlShortenerController::class, 'decode']);
Route::get('/short/{shortCode}', [UrlShortenerController::class, 'redirect']);

Route::get('/', function () {
    return view('index');
});
Route::get('/map', function(){
    return response()->json(json_decode(Storage::get('urls.json')));
});
