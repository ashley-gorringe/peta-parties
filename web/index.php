<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/execute.php';

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

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/routes.php';

?>
