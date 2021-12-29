<?php

//echo randomStringCaps(10);

use Steampixel\Route;
Route::add('/', function() {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/index.php';
});

Route::add('/plans', function() {
	echo $GLOBALS['twig']->render('plans.twig', ['user'=>$GLOBALS[user],'basket'=>$GLOBALS[basket]]);
});

/*
Route::add('/book', function() {
	echo $GLOBALS['twig']->render('booking-register.twig');
});
*/

Route::add('/book/([0-9a-zA-Z]*)', function($id) {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/plans.php';
});

Route::add('/shop', function() {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/shop.php';
});
Route::add('/shop/([0-9a-zA-Z]*)', function($id) {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/product.php';
});

Route::run('/');
?>
