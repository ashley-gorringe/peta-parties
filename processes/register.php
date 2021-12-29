<?php
$userid = randomString(30);

if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['repassword'])){
	$errorFields = array();
	if(empty($_POST['firstname'])){
		array_push($errorFields, 'firstname');
	}
	if(empty($_POST['lastname'])){
		array_push($errorFields, 'lastname');
	}
	if(empty($_POST['email'])){
		array_push($errorFields, 'email');
	}
	if(empty($_POST['password'])){
		array_push($errorFields, 'password');
	}
	if(empty($_POST['repassword'])){
		array_push($errorFields, 'repassword');
	}

	$response->status = 'error';
	$response->message = 'Missing required information.';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}

if(!validateEmail($_POST['email'])){
	$errorFields = array('email');
	$response->status = 'error';
	$response->message = 'Your email address is invalid.';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}

if($_POST['password'] != $_POST['repassword']){
	$response->status = 'error';
	$response->message = 'Password Missmatch.';
	$response->errorFields = array('password','repassword');
	echo json_encode($response);
	exit;
}
$emailCount = $database->count('users',[
	'email'=>$_POST['email']
]);
if($emailCount > 0){
	$response->status = 'error';
	$response->message = 'This email address is already registered. Try logging in instead!';
	$response->errorFields = array('email');
	echo json_encode($response);
	exit;
}

$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
//$gravatar = md5(strtolower(trim($_POST['email'])));


$database->insert('users',[
	'userid'=>$userid,
	'email'=>$_POST['email'],
	'password'=>$passwordHash,
	'firstname'=>$_POST['firstname'],
	'lastname'=>$_POST['lastname'],
]);

/*
$token = randomString(60);
$database->insert('verifyToken',[
	'userid'=>$userid,
	'token'=>$token
]);
*/

$loginToken = randomString(60);
$database->insert('loginToken',[
	'userid'=>$userid,
	'token'=>$loginToken
]);

$_SESSION['userid'] = $userid;
setcookie('token', $loginToken, time() + (86400 * 365), "/");

//$verifyUrl = $_ENV['SYSTEM_URL'].'verify/'.$token;

/*
try {
	//Recipients
	$mail->setFrom('no-reply@mail.serverbook.app', 'ServerBook');
	$mail->addAddress($_POST['email'], $_POST['name']);     // Add a recipient

	// Content
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = 'Your New ServerBook Account';
	$mail->Body    = '
	<h1>Welcome to ServerBook!</h1>
	<p>Please verify your email address by clicking on the following link, or copying it into your browser:</p>
	<p><a href="'.$verifyUrl.'">'.$verifyUrl.'</a></p>
	';
	$mail->AltBody = 'Welcome to ServerBook! Please verify your email address by copying the following link into your browser: '.$verifyUrl.'.';

	$mail->send();
	$response->status = 'success';
	$response->successRedirect = '/';
	echo json_encode($response);
	exit;
} catch (Exception $e) {
	$response->status = 'error';
	$response->message = 'Mailer Error: '.$mail->ErrorInfo;
	echo json_encode($response);
	exit;
}
*/

$response->status = 'success';
$response->successCallback = 'loginSuccess';
echo json_encode($response);
exit;
?>
