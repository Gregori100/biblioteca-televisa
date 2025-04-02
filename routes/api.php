<?php

use App\Http\Controllers\CronController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('cron')->name('cron.')->group(function () {
  Route::middleware([])->group(function () {
    Route::controller(CronController::class)->group(function () {
      Route::post('/testCron/{dryrun}', 'testCron');
    });
  });
});