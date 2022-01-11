<?php

//echo randomStringCaps(10);

use Steampixel\Route;
Route::add('/', function() {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/index.php';
});

Route::add('/plans', function() {
	echo $GLOBALS['twig']->render('plans.twig', ['user'=>$GLOBALS[user],'basket'=>$GLOBALS[basket]]);
});


Route::add('/book', function() {
	echo $GLOBALS['twig']->render('coming-soon.twig');
});
Route::add('/book/([0-9a-zA-Z]*)', function($id) {
	echo $GLOBALS['twig']->render('coming-soon.twig');
});

Route::add('/shop', function() {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/shop.php';
});
Route::add('/shop/([0-9a-zA-Z]*)', function($id) {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/product.php';
});

Route::add('/about', function() {
	echo $GLOBALS['twig']->render('coming-soon.twig');
});
Route::add('/contact', function() {
	echo $GLOBALS['twig']->render('coming-soon.twig');
});
Route::add('/customer-services', function() {
	echo $GLOBALS['twig']->render('coming-soon.twig');
});
Route::add('/returns', function() {
	echo $GLOBALS['twig']->render('coming-soon.twig');
});
Route::add('/terms', function() {
	echo $GLOBALS['twig']->render('coming-soon.twig');
});
Route::add('/privacy', function() {
	echo $GLOBALS['twig']->render('coming-soon.twig');
});

Route::run('/');
?>
