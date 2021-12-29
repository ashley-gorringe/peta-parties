<?php
$database->delete('users',['userid'=>$_SESSION['userid']]);

$_SESSION['userid'] = NULL;
setcookie('token', NULL, time() - (86400 * 365), "/");

$response->status = 'success';
echo json_encode($response);
exit;
?>
