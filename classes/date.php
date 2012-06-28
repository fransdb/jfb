<?php
class date{

	function quickDate($var){
		$dag = date("j", $var);
		$maand = date("n", $var);
		$jaar = date("Y", $var);
		$array = array(
						1 => "januari", 
						2 => "februari", 
						3 => "maart", 
						4 => "april", 
						5 => "mei", 
						6 => "juni", 
						7 => "juli", 
						8 => "augustus", 
						9 => "september", 
						10 => "oktober", 
						11 => "november", 
						12 => "december"
					   );
		
		return $dag . ' ' . $array[$maand] . ' ' . $jaar;
	}
	
	function quickTime($var){
		return date("H:i:s",$var);
	}
	function quickDateTime($var){
		$dag = date("w", $var);
		$dagcijfer = date("d", $var);
		$maand = date("n", $var);
		$tijd = date("H:i", $var);
		$dagen = array(
						0 => "Zo",
						1 => "Ma",
						2 => "Di",
						3 => "Wo",
						4 => "Do",
						5 => "Vr",
						6 => "Za"
						);
		$maanden = array(
						1 => "jan", 
						2 => "feb", 
						3 => "mrt", 
						4 => "apr", 
						5 => "mei", 
						6 => "jun", 
						7 => "jul", 
						8 => "aug", 
						9 => "sep", 
						10 => "okt", 
						11 => "nov", 
						12 => "dec"
					   );
		return $dagen[$dag] .' '. $dagcijfer .' '. $maanden[$maand] .' '. $tijd; 
	}
}
?>