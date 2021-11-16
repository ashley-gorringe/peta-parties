<?php
use Steampixel\Route;
Route::add('/', function() {
	echo $GLOBALS[twig]->render('index.twig');
});


Route::add('/login', function() {
	echo $GLOBALS[twig]->render('login.twig');
});
Route::add('/register', function() {
	echo $GLOBALS[twig]->render('register.twig');
});
Route::add('/forgot-password', function() {
	echo $GLOBALS[twig]->render('forgot-password.twig');
});

Route::run('/');
?>
