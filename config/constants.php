<?php

define('ROLE_USER', 2);
define('ROLE_GUEST', 3);
define('ROLE_ADMIN', 4);

define('PAYMENT_METHOD_COD', 2);
define('PAYMENT_METHOD_MOMO', 3);
define('PAYMENT_METHOD_VNPAY', 5);

define('ORDER_STATUS_NEW', 10);
define('ORDER_STATUS_APPROVED', 11);
define('ORDER_STATUS_SHIPPING', 12);
define('ORDER_STATUS_SHIPPED', 13);
define('ORDER_STATUS_COMPLETED', 14);
define('ORDER_STATUS_CANCELED', 15);
define('ORDER_STATUS_RETURNED', 16);

define('ORDER_STATUS_PROCESS', ['NEW', 'APPROVED', 'SHIPPING', 'SHIPPED', 'COMPLETED']);

define('PATH_DROPBOX', '/DATN/');

define('ACTION_SMS', [
    'login' => 'LOGIN',
    'register' => 'REGISTER',
    'reset_password' => 'RESET_PASSWORD',
]);

define('VNPAY_COMMAND_PAY', 'pay');
define('VNPAY_CURRENCY', 'VND');
define('VNPAY_LOCALE', 'vn');
?>
