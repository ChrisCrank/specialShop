<?php


namespace App\Config;

use App\Entity\User;

class StaticConfigs
{
    const DEV = true; ## devined over env variable

    /*SMTP Settings for sending mails*/
    // const SMTP_SERVER = SMTP_SERVER; ## smtp server
    // const SMTP_PORT = SMTP_PORT; ## smtp port
    // const SMTP_USER = SMTP_USER; ## smtp user
    // const SMTP_PASSWORD = SMTP_PASSWORD; ## smtp password

    // used for generating emails
    const BASE_URL                      = "http://localhost:3000/";
    const REGISTER_ACTIVATE_LINK        = self::BASE_URL."Register/activate/";
    const REGISTER_FORGOT_PASSWORD_LINK = self::BASE_URL."special.shop/Login/Forgot/";
    const IMAGE_URL                     = "http://localhost:82/img/";

    const PASSWORD_HASH                 = PASSWORD_BCRYPT;

    // token settings
    const AUTH_SECTRET_TOKEN        = "TXlTZWNyZXRBdXRoS2V5OTE3MmpzZGdh"; // base64 encoded key
    const AUTH_TOKEN_VALID_LONG     = 24*60*60; // 1 day -> used for rememberMe Function
    const AUTH_TOKEN_VALID_SHORT    = 60*60; // 1 hour
    const AUTH_TOKEN_VALID_PASSWORD = 60*5; // 5 minutes
    const AUTH_STATELESS            = false; // if this is true the API will always check if the Access Token is valid ( inside the DB )
    const AUTH_SERVER_TOKEN_HEADER  = 'access-token';
    const AUTH_CLIENT_TOKEN_HEADER  = 'Bearer';
    const AUTH_RENEW_TOKEN_BEFORE   = 1000*60*30; // refresh the token 30 minutes before expire

    // default language
    const DEFAULT_STORE_LANG    = "en-EN";
    const LOCALE_HEADER         = "Active-Locale";
    const CART_COOKIE_NAME      = "cart-items";

    // img upload settings
    const MAX_IMAGE_SIZE            = 5000000;
    const ALLOWED_IMAGE_EXTENSION   = ['jpg', 'jpeg', 'png', 'gif'];
    const SAVE_IMAGE_DIR = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'public/img/uploads';

    static public function generateApiKey(){
        return substr(md5(microtime()),rand(0,26),4). '-' .
            substr(md5(microtime()),rand(0,26),4) . '-' .
            substr(md5(microtime()),rand(0,26),4) . '-' .
            substr(md5(microtime()),rand(0,26),4);
    }
    static public function generateAccessToken() :string{
        return bin2hex(random_bytes(10));
    }

    static public function generateActivationToken() :string{
        return bin2hex(random_bytes(4));
    }
}
