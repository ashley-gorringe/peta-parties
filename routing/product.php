<?php

$product = $GLOBALS['database']->get('products','*',[
	'productid'=>$id,
]);

$product['price'] = $product['price'] / 100;

echo $GLOBALS['twig']->render('product.twig', ['user'=>$GLOBALS[user],'basket'=>$GLOBALS[basket],'product'=>$product]);
?>
