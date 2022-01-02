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
	$database->update('basket',[
		'quantity[+]'=>1
	],[
		'AND'=>[
			'userid'=>$_SESSION['userid'],
			'productid'=>$productid
		]
	]);

	$response->status = 'success';

	$basketItems = $database->select('basket',[
		'[>]products'=>['productid'=>'productid']
	],'*',[
		'userid'=>$_SESSION['userid']
	]);
	$checkoutPrice = 0;
	foreach ($basketItems as $key => $item) {
		$price = $item['price'] / 100;
		$basketItems[$key]['price'] = $price;
		$checkoutPrice += $price * $item['quantity'];
	}
	$response->basketTotal = $database->sum('basket','quantity',[
		'userid'=>$_SESSION['userid']
	]);
	$response->itemTotal = $database->get('basket','quantity',[
		'AND'=>[
			'userid'=>$_SESSION['userid'],
			'productid'=>$productid
		]
	]);
	$response->checkoutPrice = number_format($checkoutPrice, 2, '.', ',');

	echo json_encode($response);
	exit;
}
?>
