<?php
//Sets up Stripe Checkout integration.
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/execute.php';

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET']);

header('Content-Type: application/json');

$YOUR_DOMAIN = $_ENV['SYSTEM_URL'];

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
