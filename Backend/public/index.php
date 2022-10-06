<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';


!defined("IS_DEV")?define("IS_DEV",getenv("IS_DEV")):true;
!defined("SMTP_SERVER")?define("SMTP_SERVER",getenv("SMTP_SERVER")):false;
!defined("SMTP_PORT")?define("SMTP_PORT",getenv("SMTP_PORT")):false;
!defined("SMTP_USER")?define("SMTP_USER",getenv("SMTP_USER")):false;
!defined("SMTP_PASSWORD")?define("SMTP_PASSWORD",getenv("SMTP_PASSWORD")):false;

!defined("DS")?define("DS", DIRECTORY_SEPARATOR):false;
!defined("ROOT")?define("ROOT", __DIR__.DS."..".DS."..".DS):false;
!defined("APP")?define("APP", __DIR__.DS):false; //define root
!defined("VIEW_DIR")?define("VIEW_DIR",__DIR__.DS.'Theme'):false;

if(!empty(getallheaders()['Origin'])) {
    header("Access-Control-Allow-Origin: " . getallheaders()['Origin']);
} else {
    header("Access-Control-Allow-Origin: productDomain");
}
header('Access-Control-Allow-Headers: AccountKey, x-requested-with, Content-Type, origin, Authorization, accept, client-security-token, host, date, cookie');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 0');
header('Access-Control-Expose-Headers: ' . \App\Config\StaticConfigs::AUTH_SERVER_TOKEN_HEADER);
header('Content-Type: application/json');
header("X-Frame-Options: *");
// Access-Control headers are received during OPTIONS requests
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
