<?php

use App\Http\Controllers\Api\AdminController;
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
use App\Http\Controllers\Api\ProfileController;
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
Route::post('/user/logout', [AuthController::class, 'user_logout'])->middleware('auth:api', 'scopes:user');

Route::post('/admin/create', [AdminController::class, 'admin_create'])->middleware('admin');
Route::post('/admin/update/{id}', [AdminController::class, 'admin_update'])->middleware('admin');

Route::post('/sale/create', [SaleController::class, 'sale_create'])->middleware('admin');

Route::post('/sale/update/{id}', [SaleController::class, 'sale_update'])->middleware('sale');

Route::post('/user/create', [UserController::class, 'user_create'])->middleware(['auth:admin-api', 'auth:sale-api', 'scopes:admin,sale']);

Route::post('/user/update/{id}', [UserController::class, 'user_update'])->middleware('auth:api', 'scopes:user');

Route::apiResource('/grades', GradeController::class);
Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/subcategories', SubcategoryController::class);
Route::apiResource('/brands', BrandController::class);
Route::apiResource('/products', ProductController::class);
Route::apiResource('/percentages', PercentageController::class);

Route::post('/order', [OrderController::class, 'order'])->middleware('auth:api', 'scopes:user');
Route::post('/order_update/{id}', [OrderController::class, 'order_update'])->middleware('auth:api', 'scopes:user');
Route::get('/order_lists', [OrderController::class, 'order_lists']);
Route::get('/order_history', [OrderController::class, 'order_history']);
Route::post('/order_confirm/{id}', [OrderController::class, 'order_confirm']);
Route::post('/order_cancel/{id}', [OrderController::class, 'order_cancel']);

//admin can see sales and user info