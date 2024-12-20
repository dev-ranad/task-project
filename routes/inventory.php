
<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|
|--------------------------------------------------------------------------
| Inventory API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your Inventory application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(ProductController::class)->prefix('product')->group(function () {
    require __DIR__ . '/preset.php';
});
