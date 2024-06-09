<?php
date_default_timezone_set('Asia/Ujung_Pandang');
error_reporting(E_ERROR | E_PARSE);
require_once 'core/App.php';  //App menandakan class
require_once 'core/Controller.php';
require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'core/Flasher.php';
require_once 'core/Url.php';
require_once 'core/Helper.php';
require_once 'core/Encripsi.php';
require_once 'core/Barcode.php';

// php mailer untuk mengirim email ke email pendaftar
require_once 'core/PHPMailer-master/src/Exception.php';
require_once 'core/PHPMailer-master/src/PHPMailer.php';
require_once 'core/PHPMailer-master/src/SMTP.php';

require_once 'core/phpqrcode/qrlib.php';
require_once 'core/vendor/autoload.php';
require_once 'core/pdf/autoload.php';
require_once 'core/wkhtmltopdf/autoload.php';
