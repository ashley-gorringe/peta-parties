<?php
use Steampixel\Route;
Route::add('/', function() {
	echo $GLOBALS['twig']->render('index.twig');
});

Route::add('/plans', function() {
	echo $GLOBALS['twig']->render('plans.twig');
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
	echo $GLOBALS['twig']->render('shop.twig');
});
Route::add('/shop/([0-9a-zA-Z]*)', function($id) {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routing/shop.php';
});


Route::add('/login', function() {
	echo $GLOBALS['twig']->render('login.twig');
});
Route::add('/register', function() {
	echo $GLOBALS['twig']->render('register.twig');
});
Route::add('/forgot-password', function() {
	echo $GLOBALS['twig']->render('forgot-password.twig');
});

Route::run('/');
?>
