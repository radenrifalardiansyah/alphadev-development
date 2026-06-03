<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Website Title
|--------------------------------------------------------------------------
*/
define('TITLE',                 "Alphanet | ");
define('DOMAIN_NAME',           "alphanet.id");
define('COMPANY_NAME',          "AlphaNet");
define('COMPANY_PHONE',         "021-234-567");
define('COMPANY_ADDRESS',       "Bukit Gading Indah  RT.18/RW.8, Klp. Gading Bar., Kec. Klp. Gading, Kota Jkt Utara, Daerah Khusus Ibukota Jakarta 14240");
define('COMPANY_EMAIL',         "support@" . DOMAIN_NAME);

/*
|--------------------------------------------------------------------------
| Server/Base URL
|--------------------------------------------------------------------------
*/
define('SCHEMA', (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://");
define('BASE_URL', SCHEMA . (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '') . '/');

/*
|--------------------------------------------------------------------------
| Document Root Path
|--------------------------------------------------------------------------
*/
define('ROOTPATH', rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/');

/*
|--------------------------------------------------------------------------
| APP and Assets  Folder Name
|--------------------------------------------------------------------------
*/
define('APP_FOLDER',                'anapp');
define('ASSET_FOLDER',              'anassets');

if (defined('STDIN')) {
    // You should hardcode this for cli, otherwise it will fails.
    define('BASE_URL',              DOMAIN_NAME);
    define('DOMAIN_LIVE',           TRUE);
    define('DOMAIN_DEV',            FALSE);
} else {

    if ($_SERVER['SERVER_NAME'] == DOMAIN_NAME) {
        define('DOMAIN_LIVE',       TRUE);
        define('DOMAIN_DEV',        TRUE);
        define('DOMAIN_SHOP',       "https://rekomen.info");
        define('DOMAIN_STORE',      "https://rekomen.store");
        define('DOMAIN_NAME_SHOP',  "rekomen.info");
        define('DOMAIN_NAME_STORE', "rekomen.store");
    } else {
        define('DOMAIN_DEV',        TRUE);
        define('DOMAIN_LIVE',       FALSE);
        define('DOMAIN_SHOP',       "http://rekomendev.info");
        define('DOMAIN_STORE',      "http://rekomendev.store");
        define('DOMAIN_NAME_SHOP',  "rekomendev.info");
        define('DOMAIN_NAME_STORE', "rekomendev.store");
    }
}

/*
|--------------------------------------------------------------------------
| Page Settings
|--------------------------------------------------------------------------
*/
define('VIEW_AUTH',                 'auth/');
define('VIEW_BACK',                 'backend/');
define('VIEW_FRONT',                'frontend/');
define('VIEW_SHOP',                 'shop/');
define('VIEW_COMING_SOON',          'comingsoon/');
define('VIEW_MAINTENANCE',          'maintenance/');
define('VIEW_PATH',                 str_replace("\\", "/", VIEWPATH));

/*
|--------------------------------------------------------------------------
| Assets Path Settings
|--------------------------------------------------------------------------
*/
define('ASSET_PATH',                BASE_URL . ASSET_FOLDER . '/');
define('PROFILE_IMG',               BASE_URL . ASSET_FOLDER . '/upload/profile/');
define('PROFILE_IMG_PATH',          './'     . ASSET_FOLDER . '/upload/profile/');
define('PRODUCT_IMG',               BASE_URL . ASSET_FOLDER . '/upload/product/');
define('PRODUCT_IMG_PATH',          './'     . ASSET_FOLDER . '/upload/product/');
define('PAYMENT_IMG',               BASE_URL . ASSET_FOLDER . '/upload/payment/');
define('PAYMENT_IMG_PATH',          './'     . ASSET_FOLDER . '/upload/payment/');
define('IDCARD_IMG',                BASE_URL . ASSET_FOLDER . '/upload/id_card/');
define('IDCARD_IMG_PATH',           './'     . ASSET_FOLDER . '/upload/id_card/');
define('COVER_IMG',                 BASE_URL . ASSET_FOLDER . '/upload/cover/');
define('COVER_IMG_PATH',            './'     . ASSET_FOLDER . '/upload/cover/');
define('LOGO_RESELLER_IMG',         BASE_URL . ASSET_FOLDER . '/upload/logo/');
define('LOGO_RESELLER_IMG_PATH',    './'     . ASSET_FOLDER . '/upload/logo/');

/*
|--------------------------------------------------------------------------
| Backend Assets Path Settings
|--------------------------------------------------------------------------
*/
define('BE_CSS_PATH',               BASE_URL . ASSET_FOLDER . '/backend/css/');
define('BE_JS_PATH',                BASE_URL . ASSET_FOLDER . '/backend/js/');
define('BE_IMG_PATH',               BASE_URL . ASSET_FOLDER . '/backend/img/');
define('BE_IMG_PATH_NB',            './' . ASSET_FOLDER . '/backend/img/');
define('BE_TREE_PATH',              BASE_URL . ASSET_FOLDER . '/backend/img/tree/');
define('BE_PLUGIN_PATH',            BASE_URL . ASSET_FOLDER . '/backend/plugins/');
define('LOGO_IMG',                  BE_IMG_PATH . 'logo.png');
define('LOGO_IMG2',                 BE_IMG_PATH . 'logo2.png');
define('FAVICON',                   BE_IMG_PATH . 'favicon.png');

/*
|--------------------------------------------------------------------------
| Frontend Assets Path Settings
|--------------------------------------------------------------------------
*/
define('FE_CSS_PATH',               BASE_URL . ASSET_FOLDER . '/frontend/css/');
define('FE_JS_PATH',                BASE_URL . ASSET_FOLDER . '/frontend/js/');
define('FE_IMG_PATH',               BASE_URL . ASSET_FOLDER . '/frontend/images/');
define('FE_FONTS_PATH',             BASE_URL . ASSET_FOLDER . '/frontend/fonts/');
define('FE_VENDOR_PATH',            BASE_URL . ASSET_FOLDER . '/frontend/vendor/');

/*
|--------------------------------------------------------------------------
| Global Assets Path Settings
|--------------------------------------------------------------------------
*/
define('GLOBAL_PATH',               BASE_URL . ASSET_FOLDER . '/global/');
define('GLOBAL_CSS_PATH',           BASE_URL . ASSET_FOLDER . '/global/css/');
define('GLOBAL_PLUGINS_PATH',       BASE_URL . ASSET_FOLDER . '/global/plugins/');

/*
|--------------------------------------------------------------------------
| Coming Soon and Maintenance Assets Path Settings
|--------------------------------------------------------------------------
*/
define('COMINGSOON_CSS_PATH',       BASE_URL . ASSET_FOLDER . '/comingsoon/css/');
define('COMINGSOON_JS_PATH',        BASE_URL . ASSET_FOLDER . '/comingsoon/js/');
define('MAINTENANCE_CSS_PATH',      BASE_URL . ASSET_FOLDER . '/maintenance/css/');
define('MAINTENANCE_JS_PATH',       BASE_URL . ASSET_FOLDER . '/maintenance/js/');

/*
|--------------------------------------------------------------------------
| Shop Assets Path Settings
|--------------------------------------------------------------------------
*/
define('SH_CSS_PATH',                   BASE_URL . ASSET_FOLDER . '/shop/css/');
define('SH_JS_PATH',                    BASE_URL . ASSET_FOLDER . '/shop/js/');
define('SH_IMG_PATH',                   BASE_URL . ASSET_FOLDER . '/shop/img/');
define('SH_FONTS_PATH',                 BASE_URL . ASSET_FOLDER . '/shop/fonts/');
define('SH_PLUGIN_PATH',                BASE_URL . ASSET_FOLDER . '/shop/plugins/');

/*
|--------------------------------------------------------------------------
| Encryption / Key Config
|--------------------------------------------------------------------------
*/
define('DEBUG_KEY',         "debug123");
define('ENCRYPTION_KEY',    "q8tNvy4JZ9BS5v8MBG9EvHsjmQ2Y2S"); // is Unique
define('SECRET_IV',         "2456378494765431"); // 16bit
define('ENCRYPT_METHOD',    "aes-256-cbc");

/*
|--------------------------------------------------------------------------
| Auth Constant
|--------------------------------------------------------------------------
|
| These modes for set cookie
|
*/
define('AUTH_KEY',          '%4 N}|@na%Q;Tq$!3m?1^=u|PO_OO?!6Cr_l4h%MLbB<qu?%oj}l)+C~7;8p!vqI');
define('SECURE_AUTH_KEY',   '9`)6N;cRNBBEQG<}6P5zNS*F~#NU| uBsFb$K33-ynxgX1FE=SUP;BF-^@)Bj`CO');
define('LOGGED_IN_KEY',     '~16PA%~YtB1eWEvbozyjv01vo*4`[q3bI,O]I_].#9~S>qZHWgv/F??$=+?>uQ2l');
define('NONCE_KEY',         '))Z3:G![C@Oyb2bi=,OedV,n97J5b2M/Z&IJ*SmK*j/ApHxsRVt.cq|RDsY1mQ,)');
define('AUTH_SALT',         'w?e[S&y@,Pv7qJ&i.3*_I}{&uVm=2%B3AHt3{?PjFwvOQ|vYA^IPTf.^@,vx=d8&');
define('SECURE_AUTH_SALT',  '/wKdAgx=D?{wbw8{Mi-57JG6(+rfS:]MD{Gxp`dWyr^WyCtW]+ihseR]Rmh5p=N*');
define('LOGGED_IN_SALT',    'E(:=@55g ^ODRh9i6>PVRpW4J/u-}70N}7ALGnBey1hg7_#|-@1G<c8g]*|Fp]Q1');
define('NONCE_SALT',        'l`)q2S5Y6rY&%/Q`U,17@KfP)Okc?[Dwxqq,P*X!vh!Lp0/E|cw^d?z6D:F|4FuP');

/*
|--------------------------------------------------------------------------
| Unique Hash Cookie
|--------------------------------------------------------------------------
|
| Used to guarantee unique hash cookies
|
*/
define('COOKIEHASH', md5('[:anmember:]'));
define('MEMBER_COOKIE', 'anmember_' . COOKIEHASH);
define('PASS_COOKIE', 'anpass_' . COOKIEHASH);
define('AUTH_COOKIE', 'an_' . COOKIEHASH);
define('SECURE_AUTH_COOKIE', 'an_sec_' . COOKIEHASH);
define('LOGGED_IN_COOKIE', 'an_logged_in_' . COOKIEHASH);

/*
|--------------------------------------------------------------------------
| CSS and JS versioning
|--------------------------------------------------------------------------
|
| Used to version custom CSS and JS so that user do not have clear their browser cache manually
| DONT USE time() . caching wont work
|
*/
define('CSS_VER_AUTH',      '0.0.0.0.' . time());
define('CSS_VER_MAIN',      '0.0.0.0.' . time());
define('CSS_VER_FRONT',     '0.0.9');
define('CSS_VER_BACK',      '0.0.0.0.' . time());

define('JS_VER_AUTH',       '0.0.0.3.' . time());
define('JS_VER_MAIN',       '0.0.0.5.' . time());
define('JS_VER_FRONT',      '0.0.5');
define('JS_VER_BACK',       '0.0.0.8.' . time());
define('JS_VER_PAGE',       '0.0.0.8.' . time());

define('VER_MANIFEST',      '2');

/*
|--------------------------------------------------------------------------
| Member Type
|--------------------------------------------------------------------------
*/
define('ADMINISTRATOR', 1);
define('MEMBER',        2);
define('CUSTOMER',      0);

/*
|--------------------------------------------------------------------------
| Member Type Status
|--------------------------------------------------------------------------
*/
define('TYPE_STATUS_ADMINISTRATOR', 'admin');
define('TYPE_STATUS_STAFF',         'staff');
define('TYPE_STATUS_RESELLER',      'reseller');
define('TYPE_STATUS_DROPSHIPPER',   'dropshipper');

/*
|--------------------------------------------------------------------------
| Member Status
|--------------------------------------------------------------------------
*/
define('NONACTIVE',     0);
define('ACTIVE',        1);
define('BANNED',        2);
define('DELETED',       3);

/*
|--------------------------------------------------------------------------
| Position
|--------------------------------------------------------------------------
*/
define('POS_LEFT',      'left');
define('POS_RIGHT',     'right');

/*
|--------------------------------------------------------------------------
| Reseller Package
|--------------------------------------------------------------------------
*/
defined('PACKAGE_DROPSHIPPER')  or define('PACKAGE_DROPSHIPPER', 'dropshipper');
defined('PACKAGE_STAR1')        or define('PACKAGE_STAR1', 'star1');
defined('PACKAGE_STAR2')        or define('PACKAGE_STAR2', 'star2');
defined('PACKAGE_STAR3')        or define('PACKAGE_STAR3', 'star3');
defined('PACKAGE_STAR4')        or define('PACKAGE_STAR4', 'star4');
defined('PACKAGE_STAR5')        or define('PACKAGE_STAR5', 'star5');
defined('PACKAGE_BASIC')        or define('PACKAGE_BASIC', 'basic');

/*
|--------------------------------------------------------------------------
| Reseller Grade 
|--------------------------------------------------------------------------
*/
define('MEMBER_BASIC',      'basic');
define('MEMBER_AGENT',      'agent');
define('MEMBER_SALES',      'sales');
define('MEMBER_CUSTOMER',   'konsumen');
define('JUNIOR_MANAGER',    'junior');
define('SENIOR_MANAGER',    'senior');
define('GENERAL_MANAGER',   'general');

/*
|--------------------------------------------------------------------------
| Bonus
|--------------------------------------------------------------------------
*/
defined('BONUS_SALES')          or define('BONUS_SALES', 1);
defined('BONUS_REFERRAL')       or define('BONUS_REFERRAL', 2);
defined('BONUS_PASSUP')         or define('BONUS_PASSUP', 3);
defined('BONUS_GROUP')          or define('BONUS_GROUP', 4);
defined('BONUS_BREAK')          or define('BONUS_BREAK', 5);

/*
|--------------------------------------------------------------------------
| E-Wallet
|--------------------------------------------------------------------------
*/
defined('EWALLET_IN')           or define('EWALLET_IN', 'IN');
defined('EWALLET_OUT')          or define('EWALLET_OUT', 'OUT');

/*
|--------------------------------------------------------------------------
| E-Wallet Type
|--------------------------------------------------------------------------
*/
defined('EWALLET_TYPE_CASH')    or define('EWALLET_TYPE_CASH', 'cash');
defined('EWALLET_TYPE_GOLD')    or define('EWALLET_TYPE_GOLD', 'gold');
defined('EWALLET_TYPE_POINT')   or define('EWALLET_TYPE_POINT', 'point');

/*
|--------------------------------------------------------------------------
| Staff access
|--------------------------------------------------------------------------
*/
define('STAFF_ACCESS1',     1);
define('STAFF_ACCESS2',     2);
define('STAFF_ACCESS3',     3);
define('STAFF_ACCESS4',     4);
define('STAFF_ACCESS5',     5);
define('STAFF_ACCESS6',     6);
define('STAFF_ACCESS7',     7);
define('STAFF_ACCESS8',     8);
define('STAFF_ACCESS9',     9);
define('STAFF_ACCESS10',    10);
define('STAFF_ACCESS11',    11);
define('STAFF_ACCESS12',    12);
define('STAFF_ACCESS13',    13);
define('STAFF_ACCESS14',    14);
define('STAFF_ACCESS15',    15);
define('STAFF_ACCESS16',    16);


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/
define('TBL_PREFIX',        'an_');
define('TBL_LOG',           'an_log');
define('TBL_LOG_CRON',      'an_log_cron');
define('TBL_LOG_NOTIF',     'an_log_notif');
define('TBL_LOG_ACTIONS',   'an_log_action');
define('TBL_LOG_FLIP',      'an_log_flip');
define('TBL_LOG_FASTPAY',   'an_log_fastpay');
define('TBL_OPTIONS',       'an_options');
define('TBL_SESSIONS',      'an_sessions');
define('TBL_PRODUCT',       'an_product');
define('TBL_DISCOUNT',      'an_promo_code');
define('TBL_SHOP_ORDER',    'an_shop_order');
define('TBL_USERS',         'an_member');


/*
|--------------------------------------------------------------------------
| Faspay SendMe
|--------------------------------------------------------------------------
|
| Fastpay SendMe Location
|
*/
define('FASPAY_SENDME_LIB', realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR . '/libraries/faspay/SendMe.php');

/*
|--------------------------------------------------------------------------
| Mailer Engine
|--------------------------------------------------------------------------
|
| Swift Mailer Location
|
*/
define('SWIFT_MAILSERVER', realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR . '/libraries/swiftmailer/swift_required.php');
