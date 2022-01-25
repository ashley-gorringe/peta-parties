<?php
//If no user is logged in, end the current process and send back an error message.
if(!isset($_SESSION['userid'])){
	$response->status = 'error';
	$response->message = 'Please log in or create an account to continue.';
	echo json_encode($response);
	exit;
}

//Get the desired productid and check that it exists in the database. If not, end the current process and send back an error message.
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

//Count how many times the product already exists in the user's basket.
$existingCount = $database->count('basket',[
	'AND'=>[
		'userid'=>$_SESSION['userid'],
		'productid'=>$productid
	]
]);

if($existingCount < 1){
	//If the product doesn't already exist in the user's basket, end the current process and send back an error message.
	$response->status = 'error';
	$response->message = 'An error has occured. Please try again.';
	echo json_encode($response);
	exit;
}else{
	//The product does exist in the user's basket.

	//Increase the quantity of the product in the user's basket
	$database->update('basket',[
		'quantity[+]'=>1
	],[
		'AND'=>[
			'userid'=>$_SESSION['userid'],
			'productid'=>$productid
		]
	]);

	//Calculate all total prices and quantities, prepare to send data back to front-end.
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

	//Process has been successful, send success data back to AJAX to take actions and report success to user.
	$response->status = 'success';
	echo json_encode($response);
	exit;
}
?>
