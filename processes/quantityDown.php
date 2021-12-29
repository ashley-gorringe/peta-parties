<?php

if(!isset($_SESSION['userid'])){
	$response->status = 'error';
	$response->message = 'Please log in or create an account to continue.';
	echo json_encode($response);
	exit;
}

$productid = $_POST['productid'];

$count = $database->count('products',[
	'productid'=>$productid
]);
if($count < 1){
	$response->status = 'error';
	$response->message = 'An error has occured. Please try again.';
	echo json_encode($response);
	exit;
}

$existingCount = $database->count('basket',[
	'AND'=>[
		'userid'=>$_SESSION['userid'],
		'productid'=>$productid
	]
]);

if($existingCount < 1){
	$response->status = 'error';
	$response->message = 'An error has occured. Please try again.';
	echo json_encode($response);
	exit;
}else{

	$currentQuantity = $database->get('basket','quantity',[
		'AND'=>[
			'userid'=>$_SESSION['userid'],
			'productid'=>$productid
		]
	]);

	if($currentQuantity <= 1){
		$database->delete('basket',[
			'AND'=>[
				'userid'=>$_SESSION['userid'],
				'productid'=>$productid
			]
		]);
		$response->status = 'success';
		echo json_encode($response);
		exit;
	}else{
		$database->update('basket',[
			'quantity[-]'=>1
		],[
			'AND'=>[
				'userid'=>$_SESSION['userid'],
				'productid'=>$productid
			]
		]);
		$response->status = 'success';
		echo json_encode($response);
		exit;
	}
}
?>
