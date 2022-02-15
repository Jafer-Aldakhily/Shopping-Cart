<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Client Routes
Route::get('/',[ClientController::class , 'home']);
Route::get('/shop' , [ClientController::class , 'shop']);
Route::get('/cart' , [ClientController::class , 'cart']);
Route::get('/checkout' , [ClientController::class , 'checkout']);
Route::get('/login' , [ClientController::class , 'login']);
Route::get('/signup' , [ClientController::class , 'signup']);

// Admin Routes
Route::get('/admin' , [AdminController::class , 'admin']);

// Admin Categories
Route::get('/addcategory' , [CategoryController::class , 'addCategory']);
Route::get('/categories' , [CategoryController::class , 'categories']);
Route::post('/savecategory' , [CategoryController::class , 'saveCategory']);
Route::get('/editcategory/{id}' , [CategoryController::class , 'editCategory']);
Route::post('/updatecategory' , [CategoryController::class , 'updateCategory']);
Route::get('/deletecategory/{id}' , [CategoryController::class , 'deleteCategory']);

// Admin Sliders
Route::get('/addslider' , [SliderController::class , 'addSlider']);
Route::get('/sliders' , [SliderController::class , 'sliders']);
Route::post('/saveslider' , [SliderController::class , 'saveSlider']);
Route::get('/editslider/{id}' , [SliderController::class , 'editSlider']);
Route::post('/updateslider' , [SliderController::class , 'updateSlider']);
Route::get('/deleteslider/{id}' , [SliderController::class , 'deleteSlider']);
Route::get('/activeslider/{id}' , [SliderController::class , 'active_slider']);
Route::get('/unactiveslider/{id}' , [SliderController::class , 'unactive_slider']);

// Admin Products
Route::get('/addproduct' , [ProductController::class , 'addproduct']);
Route::get('/products' , [ProductController::class , 'products']);
Route::post('/saveproduct' , [ProductController::class , 'saveProduct']);
Route::get('/editproduct/{id}' , [ProductController::class , 'editProduct']);
Route::post('/updateproduct/{id}' , [ProductController::class , 'updateProduct']);
Route::get('/deleteproduct/{id}' , [ProductController::class , 'deleteProduct']);
Route::get('/activeproduct/{id}' , [ProductController::class , 'active_product']);
Route::get('/unactiveproduct/{id}' , [ProductController::class , 'unactive_product']);

// Client Shop
Route::get('/products-by-category/{category_name}' , [ClientController::class , 'get_product_by_category']);

// Client Cart
Route::get('/addtocart/{id}' , [CartController::class , 'addToCart']);
Route::post('/update-quntity/{id}' , [CartController::class , 'update_qty']);
Route::get('/remove-item/{id}' , [CartController::class , 'removeItem']);

// Client Authentication
Route::post('/access-account' , [ClientController::class , 'access_account']);
Route::post('/creat-account' , [ClientController::class , 'create_account']);
Route::get('/logout' , [ClientController::class , 'logout']);


// Order Routes
Route::get('/orders' , [OrderController::class , 'orders']);
Route::post('/post-checkout' , [OrderController::class , 'post_checkout']);

// pdf Routes
Route::get('/view-pdf/{id}' , [PdfController::class , 'view_pdf']);

// Payment Checkout Route
Route::get('/payment-success' , [OrderController::class , 'payment_success']);













// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';
