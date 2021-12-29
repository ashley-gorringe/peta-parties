<?php
define('BASE_PATH',dirname($_SERVER['DOCUMENT_ROOT']).'/');
session_start();
date_default_timezone_set('Europe/London');

require BASE_PATH.'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$loader = new \Twig\Loader\FilesystemLoader(BASE_PATH.'templates');
$twig = new \Twig\Environment($loader);

use Medoo\Medoo;
$database = new Medoo([
    'database_type' => $_ENV['DB_TYPE'],
    'database_name' => $_ENV['DB_DATABASE'],
    'server' => $_ENV['DB_SERVER'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
]);

// Initialize PHP Mailer with Mailgun SMTP
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);
$mail->SMTPDebug = 0;
$mail->isSMTP();
$mail->Host       = $_ENV['MAIL_HOST'];
$mail->SMTPAuth   = $_ENV['MAIL_SMTPAUTH'];
$mail->Username   = $_ENV['MAIL_USERNAME'];
$mail->Password   = $_ENV['MAIL_PASSWORD'];
$mail->SMTPSecure = $_ENV['MAIL_SMTPSECURE'];
$mail->Port       = $_ENV['MAIL_PORT'];

use Slim\Http\Request;
use Slim\Http\Response;
use Stripe\Stripe;

date_default_timezone_set('Europe/London');
require_once BASE_PATH.'functions.php';

if(isset($_COOKIE['token'])){
	$tokenCount = $database->count('loginToken',['token'=>$_COOKIE['token']]);
	if($tokenCount !== 1){
		$_SESSION['userid'] = NULL;
		setcookie('token', NULL, time() - (86400 * 365), "/");
	}else{
		$token = $database->get('loginToken',[
			'[>]users'=>'userid'
		],[
			'users.userid'
		],[
			'loginToken.token'=>$_COOKIE['token']
		]);
		$_SESSION['userid'] = $token['userid'];
	}
}else{
	$_SESSION['userid'] = null;
}
?>
