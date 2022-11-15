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
require __DIR__ . '/Store/store.php';

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

// Warehouse
require __DIR__ . '/Warehouse/warehouse.php';

// Specification
require __DIR__ . '/Specification/specification.php';

// Order
require __DIR__ . '/Order/order.php';

// ShippingMethod
require __DIR__. '/ShippingMethod/shipping_method.php';

// Location
require __DIR__. '/Location/location.php';

// Cart
require __DIR__ . '/Cart/cart.php';
//Post Categories
require __DIR__ . '/postCategories/postCategories.php';

require __DIR__ . '/Post/post.php';

// colors
require __DIR__ . '/Colors/colors.php';

<<<<<<< HEAD
// Promotion
require __DIR__ . '/Promotion/promotion.php';

// Coupon
require __DIR__ . '/Coupon/coupon.php';
=======
// Biến thể
require __DIR__ . '/ProductVariants/productVariants.php';
>>>>>>> dev_nha
