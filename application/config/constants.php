<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
define('SALT',  '#*1nf0-*%');
define('PATH_APP',  'http://localhost/arnouped/core/application/');
define('NOMBRE_EMPRESA',		'CAVAL');

define('TURBOSMTP_USER',		'rodrigo.gonzalez@arnou.cl');
define('TURBOSMTP_PASS',		'P6nLKAvx');

define('API_KEY_MAIL',		'9f5f5e6d34432e20890ba70e309840d8de5b9755ff6e18446f57d00bba93b115-jAcCmIMdDkXyrH0B');
define('ENVIO_MAIL',		TRUE);
define('PERIODOS_GRATIS',		2);
define('DIAS_AVISO',		5);
define('RUTA_VUELTA_WEBPAY',		'https://www.tugastocomun.cl/app/guest/webpay');

define('RUTA_VUELTA_WEBPAY_PROP',		'http://localhost/app_ggcc_v3/payments/webpay_prop');
define('BASE_URL_PAYKU',		'app.payku.cl');
define('TOKEN_PUBLICO_PAYKU',		'tkpu12dd172cabd4122cf6ad6448e0df');

/* End of file constants.php */
/* Location: ./application/config/constants.php */