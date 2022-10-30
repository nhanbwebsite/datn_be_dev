<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication
require __DIR__ . '/Auth/auth.php';

// User
require __DIR__ . '/User/user.php';

// Role
require __DIR__ . '/Role/role.php';

// GroupPermission
require __DIR__ . '/GroupPermission/group_permission.php';

// RolePermission
require __DIR__ . '/RolePermission/role_permission.php';

// Permission
require __DIR__ . '/Permission/permission.php';

// Categories routers
require __DIR__ . '/Categories/categories.php';

// Subcategories routes
require __DIR__ . '/Subcategories/subcategories.php';

// brands routers
require __DIR__ . '/Brands/brands.php';

// Product
require __DIR__ . '/Products/products.php';

// Stores routes
require __DIR__ . '/Stores/stores.php';

// City routes
require __DIR__ . '/City/city.php';

// Wishlist
require __DIR__ . '/Wishlist/wishlist.php';

// Comment
require __DIR__. '/Comment/comment.php';

// AddressNote
require __DIR__ . '/AddressNote/address_note.php';

// OrderStatus
require __DIR__ . '/OrderStatus/order_status.php';

// PaymentMethod
require __DIR__ . '/PaymentMethod/payment_method.php';

// productImportSlip
require __DIR__ . '/ProductImportSlip/productImportSlip.php';
