<?php

$products = $GLOBALS['database']->select('products','*');

foreach ($products as $key => $product){
	$products[$key]['price'] = $product['price'] / 100;
}

echo $GLOBALS['twig']->render('shop.twig', ['user'=>$GLOBALS[user],'basket'=>$GLOBALS[basket],'products'=>$products]);
?>
