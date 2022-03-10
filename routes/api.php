<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PercentageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SubcategoryController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/admin/login', [AuthController::class, 'admin_login']);
Route::post('/admin/logout', [AuthController::class, 'admin_logout'])->middleware('admin');

Route::post('/sale/login', [AuthController::class, 'sale_login']);
Route::post('/sale/logout', [AuthController::class, 'sale_logout'])->middleware('sale');

Route::post('/user/login', [AuthController::class, 'user_login']);
Route::post('/user/logout', [AuthController::class, 'user_logout'])->middleware('auth:api');

Route::apiResource('/sales', SaleController::class)->middleware('admin');

Route::apiResource('/users', UserController::class)->middleware(['admin_or_sale']);

Route::apiResource('/grades', GradeController::class);

Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/subcategories', SubcategoryController::class);
Route::apiResource('/brands', BrandController::class);
Route::apiResource('/products', ProductController::class);
Route::apiResource('/percentages', PercentageController::class);

Route::post('/order', [OrderController::class, 'order']);
Route::get('/order_lists', [OrderController::class, 'order_lists']);
Route::get('/order_history', [OrderController::class, 'order_history']);


//all user profile update
//order confirm/cancel
//in pending state order update
//after order confirm, reduce instock related product