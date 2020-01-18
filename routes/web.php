<?php

// Authentication routes
// Handled by Laravel router
Auth::routes();

// All other routes
// Handled by Vue Router
Route::get('/{any}', 'AppController@index')->where('any', '.*');
