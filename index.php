<?php
require 'Slim/Slim.php';
require 'config/config.php';
require 'classes/constructor.php';

$app	= new Slim();



// get
$app->get('/feed/', 'getFeed');

// post
$app->post('/checkin/', 'checkin');
$app->post('/post/', 'post');

// put

// delete

function post(){
	$debug = true;
	$constructor = new constructor();
	$data = $_POST;
	$data['timestamp'] = time();
	if($debug){
		if($constructor->model->post($data)){
			echo 'gelukt';
		}else{
			echo 'Er ging iets mis.';
		}
	}else{
		return json_encode('result');
	}
}

function getFeed(){
	$constructor = new constructor();
	$user_id = 3;
	echo json_encode($constructor->model->getFeed($user_id));
}

function checkin(){
	$constructor = new constructor();
	$array = $_POST;
	$array['time'] = time();
	echo json_encode($constructor->model->checkin($array));
}

function postLogin(){

}

function getConcerts(){

}

$app->run();