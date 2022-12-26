<?php

define('ROLE_USER', 2);
define('ROLE_GUEST', 3);
define('ROLE_ADMIN', 4);

define('PAYMENT_METHOD_COD', 2);
define('PAYMENT_METHOD_MOMO', 3);
define('PAYMENT_METHOD_VNPAY', 5);
define('PAYMENT_METHOD_ID_ONLINE', [3, 5]);

define('ORDER_STATUS_NEW', 10);
define('ORDER_STATUS_APPROVED', 11);
define('ORDER_STATUS_SHIPPING', 12);
define('ORDER_STATUS_SHIPPED', 13);
define('ORDER_STATUS_COMPLETED', 14);
define('ORDER_STATUS_CANCELED', 15);
define('ORDER_STATUS_RETURNED', 16);

define('ORDER_STATUS_CAN_CANCEL', [10, 11]);

define('ORDER_STATUS_PROCESS', [10, 11, 12, 13]);

define('PATH_UPLOAD', '/images/');
define('EXTENSION_UPLOAD', ['jpeg','jpg','jpe','gif','png','svg', 'webp']);

define('ACTION_SMS', [
    'login' => 'LOGIN',
    'register' => 'REGISTER',
]);

define('VNPAY_COMMAND_PAY', 'pay');
define('VNPAY_CURRENCY', 'VND');
define('VNPAY_LOCALE', 'vn');
?>
