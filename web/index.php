<?php
//All traffic is redirected to this index.php file via the .htaccess file.

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/execute.php';//Sets up the full PHP environment, loads in dependancies, functions, and checks to see if a user is signed in.

//If a user is logged in, create a global $user variable containing all of the required data. Also includes $basket data if a basket is available.
if(isset($_SESSION['userid'])){
	$user = $database->get('users','*',[
		'userid'=>$_SESSION['userid'],
	]);

	$basketCount = $database->count('basket',[
		'userid'=>$_SESSION['userid']
	]);
	if($basketCount < 1){
		$basket = null;
	}else{
		$basket['total'] = $database->sum('basket','quantity',[
			'userid'=>$_SESSION['userid']
		]);
		$basket['items'] = $database->select('basket',[
			'[>]products'=>['productid'=>'productid']
		],'*',[
			'userid'=>$_SESSION['userid']
		]);
		$basket['totalPrice'] = 0;
		foreach ($basket['items'] as $key => $item) {
			$price = $item['price'] / 100;
			$basket['items'][$key]['price'] = $price;
			$basket['totalPrice'] += $price * $item['quantity'];
		}
	}
}else{
	$user = null;
	$basket = null;
}

//Includes traffic routes, this determines what happens based on what URL the visitor is attempting to access.
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/routes.php';
?>
