<?php

$products = $GLOBALS['database']->select('products','*',[
	'LIMIT'=>4
]);

foreach ($products as $key => $product){
	$products[$key]['price'] = $product['price'] / 100;
}

echo $GLOBALS['twig']->render('index.twig', ['user'=>$GLOBALS[user],'basket'=>$GLOBALS[basket],'products'=>$products]);
?>
