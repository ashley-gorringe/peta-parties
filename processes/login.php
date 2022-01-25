<?php
$userid = randomString(30);//Generates a 30 character random alphanumeric string.

//Checks that required form field data has been send to the back-end. If not, end the current process and send back an error message including the missing form fields.
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

//Checks that the email field is using a valid email address. If not, end the current process and send back an error message.
if(!validateEmail($_POST['email'])){
	$errorFields = array('email');
	$response->status = 'error';
	$response->message = 'Your email address is invalid.';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}

//Checks that the email address exists in the database. If not, end the current process and send back an error message.
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
	//The email address does exist in the database.

	//Get the password hash for the user in the database that matches the email address.
	$passwordHash = $database->get('users','password',[
		'email'=>$_POST['email']
	]);

	//Check if the submitted password matches the stored password hash.
	if(password_verify($_POST['password'],$passwordHash)){
		//The passwords match

		//Get the userid associated to the email address from the database, and store it in the Session userid. The user is now logged in at a session level.
		$_SESSION['userid'] = $database->get('users','userid',[
			'email'=>$_POST['email']
		]);

		//Create a login cookie token, store it in the database, and set a cookie.
		$loginToken = randomString(60);
		$database->insert('loginToken',[
			'userid'=>$_SESSION['userid'],
			'token'=>$loginToken
		]);
		setcookie('token', $loginToken, time() + (86400 * 365), "/");

		//Process has been successful, send success data back to AJAX to take actions and report success to user.
		$response->status = 'success';
		$response->successCallback = 'loginSuccess';
		echo json_encode($response);
		exit;
	}else{
		//The passwords did not match.
		//End the current process and send back an error message.
		$response->status = 'error';
		$response->message = 'Email Address or Password is incorrect.';
		$response->errorFields = array('email','password');

		echo json_encode($response);
		exit;
	}
}


?>
