<?php
require 'date.php';
require 'classes/model.php';
require 'classes/database.php';
require 'classes/result.php';

class constructor{	
	var $connection;
	
	function __construct(){
		$this->connect = new database;
		$this->model = new model($this);
		$this->date = new date;
		$this->result = new result($this);
	}
	
	
}