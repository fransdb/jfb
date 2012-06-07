<?php
require 'Slim/Slim.php';
require_once('model/model.php');

$model 	= new model();
$db 	= new db();
$app 	= new Slim();

$app->get('/', function () {
    $template = 'hello world';
    echo $template;
});
$app->get('/feed/:user_id', function($user_id){
	echo $user_id;
});
$app->post('/post', function () {
    echo 'This is a POST route';
});
$app->put('/put', function () {
    echo 'This is a PUT route';
});
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
});
$app->run();