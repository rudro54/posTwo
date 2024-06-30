<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//User Login Registration API Routes

Route::post('/user-registration', [UserController::class, 'userRegistration']);

Route::post('/user-login', [UserController::class, 'userLogin']);

Route::post('/send-otp', [UserController::class, 'sendOTPCode']);

Route::post('/verify-otp', [UserController::class, 'verifyOTp']);

Route::post('/reset-password', [UserController::class, 'resetPassword'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/user-profile', [UserController::class, 'userProfile'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/user-update', [UserController::class, 'updateProfile'])->middleware([TokenVerificationMiddleware::class]);


Route::get('/user-logout', [UserController::class, 'userLogout']);

//Page Routes

Route::get('/userLogin', [UserController::class, 'LoginPage']);

Route::get('/userRegistration', [UserController::class, 'RegistrationPage']);

Route::get('/sendOtp', [UserController::class, 'SendOtpPage']);

Route::get('/verifyOtp', [UserController::class, 'VerifyOtpPage']);

Route::get('/resetPassword', [UserController::class, 'ResetPasswordPage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/dashboard', [UserController::class, 'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/userProfile', [UserController::class, 'ProfilePage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/categoryPage', [CategoryController::class, 'CategoryPage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/customerPage', [CustomerController::class, 'CustomerPage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/productPage', [ProductController::class, 'ProductPage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/invoicePage', [InvoiceController::class, 'InvoicePage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/salePage', [InvoiceController::class, 'SalePage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/reportPage', [ReportController::class, 'ReportPage'])->middleware([TokenVerificationMiddleware::class]);




//Category API

Route::post('/create-category', [CategoryController::class, 'CategoryCreate'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/list-category', [CategoryController::class, 'CategoryList'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/delete-category', [CategoryController::class, 'CategoryDelete'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/update-category', [CategoryController::class, 'CategoryUpdate'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/category-by-id', [CategoryController::class, 'CategoryById'])->middleware([TokenVerificationMiddleware::class]);


//Customer API

Route::post('/create-customer', [CustomerController::class, 'CustomerCreate'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/list-customer', [CustomerController::class, 'CustomerList'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/delete-customer', [CustomerController::class, 'CustomerDelete'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/update-customer', [CustomerController::class, 'CustomerUpdate'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/customer-by-id', [CustomerController::class, 'CustomerById'])->middleware([TokenVerificationMiddleware::class]);


// Product API

Route::post('/create-product', [ProductController::class, 'CreateProduct'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/delete-product', [ProductController::class, 'DeleteProduct'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/product-by-id', [ProductController::class, 'ProductById'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/list-product', [ProductController::class, 'ProductList'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/update-product', [ProductController::class, 'ProductUpdate'])->middleware([TokenVerificationMiddleware::class]);


//Invoice API

Route::post('/invoice-create', [InvoiceController::class, 'InvoiceCreate'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/invoice-select', [InvoiceController::class, 'InvoiceSelect'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/invoice-detail', [InvoiceController::class, 'InvoiceDetails'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/invoice-delete', [InvoiceController::class, 'InvoiceDelete'])->middleware([TokenVerificationMiddleware::class]);



//Dashboard & Report Api 

Route::get('/summary', [DashboardController::class, 'Summary'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/sales-report/{FromDate}/{ToDate}', [ReportController::class, 'SalesReport'])->middleware([TokenVerificationMiddleware::class]);