<?php
$userid = randomString(30);//Generates a 30 character random alphanumeric string.

//Checks that required form field data has been send to the back-end. If not, end the current process and send back an error message including the missing form fields.
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

//Checks that the email field is using a valid email address. If not, end the current process and send back an error message.
if(!validateEmail($_POST['email'])){
	$errorFields = array('email');
	$response->status = 'error';
	$response->message = 'Your email address is invalid.';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}

//Checks that the Password and Password Re-enter fields match each other. If not, end the current process and send back an error message.
if($_POST['password'] != $_POST['repassword']){
	$response->status = 'error';
	$response->message = 'Password Missmatch.';
	$response->errorFields = array('password','repassword');
	echo json_encode($response);
	exit;
}

//Counts occurences of the email address in the users table of the database. If the email already exists in the database, end the current process and send back an error message.
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

//Submitted data has been validated for this process, actions can now be taken.

//Hash the submitted password.
$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

//Create a new record in the users table including all the submitted data, the hashed password, and the randomised string as a unique key.
$database->insert('users',[
	'userid'=>$userid,
	'email'=>$_POST['email'],
	'password'=>$passwordHash,
	'firstname'=>$_POST['firstname'],
	'lastname'=>$_POST['lastname'],
]);

//Create a cookie token in the database associated with the userid.
$loginToken = randomString(60);
$database->insert('loginToken',[
	'userid'=>$userid,
	'token'=>$loginToken
]);

//Set the Session userid and set a token cookie. The user is now logged in and a cookie is set to remember their login state.
$_SESSION['userid'] = $userid;
setcookie('token', $loginToken, time() + (86400 * 365), "/");

//Process has been successful, send success data back to AJAX to take actions and report success to user.
$response->status = 'success';
$response->successCallback = 'loginSuccess';
echo json_encode($response);
exit;
?>
