<?php

define('ROLE_USER', 2);
define('ROLE_GUEST', 3);
define('ROLE_ADMIN', 4);

define('PAYMENT_METHOD_COD', 0);
define('PAYMENT_METHOD_ONLINE', 1);

define('ORDER_STATUS_NEW', 'NEW');
define('ORDER_STATUS_APPROVED', 'APPROVED');
define('ORDER_STATUS_SHIPPING', 'SHIPPING');
define('ORDER_STATUS_SHIPPED', 'SHIPPED');
define('ORDER_STATUS_COMPLETED', 'COMPLETED');
define('ORDER_STATUS_CANCELED', 'CANCELED');

define('ORDER_STATUS_PROCESS', ['NEW', 'APPROVED', 'SHIPPING', 'SHIPPED', 'COMPLETED', 'CANCELED']);
define('IMAGE_PATH', 'images');

define('ACTION_SMS', [
    'login' => 'LOGIN',
    'register' => 'REGISTER',
    'reset_password' => 'RESET_PASSWORD',
])
?>
