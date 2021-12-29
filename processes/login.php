<?php
$userid = randomString(30);

if(empty($_POST['email']) || empty($_POST['password'])){
	$errorFields = array();

	if(empty($_POST['email'])){
		array_push($errorFields, 'email');
	}
	if(empty($_POST['password'])){
		array_push($errorFields, 'password');
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
$emailCount = $database->count('users',[
	'email'=>$_POST['email']
]);
if($emailCount < 1){
	$response->status = 'error';
	$response->message = 'Your email address or password is incorrect.';
	$response->errorFields = array('email','password');
	echo json_encode($response);
	exit;
}else{
	$passwordHash = $database->get('users','password',[
		'email'=>$_POST['email']
	]);

	if(password_verify($_POST['password'],$passwordHash)){
		$_SESSION['userid'] = $database->get('users','userid',[
			'email'=>$_POST['email']
		]);

		$loginToken = randomString(60);
		$database->insert('loginToken',[
			'userid'=>$_SESSION['userid'],
			'token'=>$loginToken
		]);
		setcookie('token', $loginToken, time() + (86400 * 365), "/");


		$response->status = 'success';
		$response->successCallback = 'loginSuccess';
		echo json_encode($response);
		exit;
	}else{
		$response->status = 'error';
		$response->message = 'Email Address or Password is incorrect.';
		$response->errorFields = array('email','password');

		echo json_encode($response);
		exit;
	}
}


?>
