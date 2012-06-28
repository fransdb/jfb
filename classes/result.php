<?php
class result{
	var $data = array();
	function __construct($constructor){
		$this->constructor = $constructor;
	}
	
	public function countRows($result){
		return @mysql_num_rows($result);
	}
	public function fetchRows($result){		
		while($x = mysql_fetch_assoc($result)){
			$this->data[] = $x;
		}
		return $this->data;
	}
	public function fetchObjects($result){
		while($x = mysql_fetch_objects($result)){
			$this->data[] = $x;
		}
		return $this->data;
	}
	public function fetchArray($result){
		while($x = mysql_fetch_array($result)){
			$this->data[] = $x;
		}
		return $this->data;
	}
	public function affectedRows($result){
		return @mysql_affected_rows();
	}
	public function singleResult($result){
		return @mysql_result($result, 0, 0);
	}
	public function execId($result){
		return @mysql_insert_id();
	}
}
?>