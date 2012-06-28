<?php
class database{

	var $connection;
	
	function __construct(){
		$this->connect();
	}
	
	private function connect(){
		$this->connection = @mysql_connect(_dbhost_,_dbuser_,_dbpass_);
		if($this->connection){
			$select = @mysql_select_db(_dbase_);
			if($select){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function query($query){
		return mysql_query($query);
	}
	
	public function close(){
		mysql_close($this->connection);
	}
}
?>