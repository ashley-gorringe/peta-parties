<?php
//Delete the active login token from the database.
$database->delete('loginToken',['token'=>$_COOKIE['token']]);

//Empty the session userid, and the login token cookie.
$_SESSION['userid'] = NULL;
setcookie('token', NULL, time() - (86400 * 365), "/");

//Process has been successful, send success data back to AJAX to take actions and report success to user.
$response->status = 'success';
echo json_encode($response);
exit;
?>
