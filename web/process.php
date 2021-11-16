<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/execute.php';
header('Content-Type: application/json');
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(empty($_POST['action'])){
	$response->status = 'error';
    $response->message = 'Critical Error: No process action provided.';
    echo json_encode($response);
    exit;
}else{
	switch($_POST['action']){
		case 'register':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/register.php';
			break;
		case 'login':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/login.php';
			break;
		case 'logout':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/logout.php';
			break;

		case 'application-start':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/application/start.php';
			break;
		case 'application-cancel':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/application/cancel.php';
			break;
		case 'basicInfo':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/application/basicInfo.php';
			break;
		case 'contactDetails':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/application/contactDetails.php';
			break;
		case 'artistBio':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/application/artistBio.php';
			break;
		case 'availability':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/application/availability.php';
			break;
	}
}
?>
