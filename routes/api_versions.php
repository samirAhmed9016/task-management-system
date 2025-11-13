<?php

use Illuminate\Support\Facades\Route;


// $versions = ['v1']; // add v2, v3 in the future

// foreach ($versions as $version) {
//     Route::middleware('api')
//         ->prefix("api/{$version}")
//         ->group(base_path("routes/api/{$version}.php"));
// }



// Scan the routes/api folder for any v*.php files
$files = glob(base_path('routes/api/v*.php'));

foreach ($files as $file) {
    // Extract the version from the filename
    $version = basename($file, '.php'); // e.g., "v1"

    Route::middleware('api')
        ->prefix("api/{$version}")
        ->group($file);
}
