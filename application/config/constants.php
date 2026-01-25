<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
define('SHOW_DEBUG_BACKTRACE', TRUE);

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
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
define('TURBOSMTP_USER',		'rgonzalez@tugastocomun.cl');
define('TURBOSMTP_PASS',		'S5mbPgpL#1'); //S5mbPgpL#1
//define('TURBOSMTP_USER',		'rodrigo.gonzalez@arnou.cl');
//define('TURBOSMTP_PASS',		'P6nLKAvx');
define('API_KEY_MAIL',		'9f5f5e6d34432e20890ba70e309840d8de5b9755ff6e18446f57d00bba93b115-jAcCmIMdDkXyrH0B');

define('ENVIO_MAIL',		TRUE);
define('PERIODOS_GRATIS',		2);
define('DIAS_AVISO',		5);
define('RUTA_VUELTA_WEBPAY',		'https://www.tugastocomun.cl/app/guest/webpay');

define('RUTA_VUELTA_WEBPAY_PROP',		'http://localhost/app_ggcc_v3/payments/webpay_prop');
define('BASE_URL_PAYKU',		'app.payku.cl');
define('TOKEN_PUBLICO_PAYKU',		'tkpu12dd172cabd4122cf6ad6448e0df');

define('PAGO_ONLINE_PRUEBA',		FALSE);



define('PORCT_FONASA',		0.039);//2.5% //1.8% //3.9%  //1.6% //3.9% //1% //3.9% // 2.1% // 1.6%
define('PORCT_INP',		0.031); //4.5% //5.2% //3.1% //5.4% //3.1%  //6% //3.1% // 4.9 % // 5.4 %



define('MANTENCION',		FALSE);