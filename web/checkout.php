<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/execute.php';

\Stripe\Stripe::setApiKey('sk_test_51KC6j1JkjlpMJLv5a6hICG37qxSemDcp4o2RX4EKDaFDuEnyuBltuS597eCStC3nKh3Bk5BLqvZC9stGSKMMfwtb00EiFjONwB');

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost:8888';

$basketItems = $database->select('basket',[
	'[>]products'=>['productid'=>'productid']
],'*',[
	'userid'=>$_SESSION['userid']
]);

$line_items = array();
foreach ($basketItems as $item) {
	$line_items[]= array(
		'price'=>$item['stripePriceID'],
		'quantity'=>$item['quantity']
	);
}

//die(var_dump($line_items));

$checkout_session = \Stripe\Checkout\Session::create([
  'line_items' => $line_items,
  'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/',
    'cancel_url' => $YOUR_DOMAIN . '/',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);



?>
