<?php
class model{
	var $connection;
	
	function __construct($constructor){
		$this->connect = new database;
		$this->constructor = $constructor;
	}
	public function getFeed($user_id = null){
		$sql = "
			SELECT 
				jfb_post.post_id,
				jfb_user.jfb_user_id,
				jfb_user.jfb_user_name,
				jfb_user.jfb_user_facebook,
				jfb_user.jfb_user_twitter,
				jfb_concert.jfb_concert_id,
				jfb_concert.concert_name,
				jfb_post_type.media_type,
				jfb_post.timestamp,
				jfb_post.public,
				jfb_post.text,
				jfb_post.media
			FROM
				jfb_user_abbo
			LEFT JOIN
				jfb_concert_to_artist
			ON
				jfb_concert_to_artist.jfb_artist_id = jfb_user_abbo.jfb_artist_id
			LEFT JOIN
				jfb_post
			ON
				jfb_post.jfb_concert_id = jfb_concert_to_artist.jfb_concert_id
			LEFT JOIN
				jfb_post_type
			ON
				jfb_post_type.id = jfb_post.jfb_media_type
			LEFT JOIN
				jfb_check_in
			ON
				jfb_check_in.jfb_concert_id = jfb_post.jfb_concert_id
			LEFT JOIN
				jfb_user
			ON
				jfb_user.jfb_user_id = jfb_post.jfb_user_id
			LEFT JOIN
				jfb_concert
			ON
				jfb_concert.jfb_concert_id = jfb_concert_to_artist.jfb_concert_id
			WHERE
				jfb_user_abbo.jfb_user_id = '". $user_id ."'
			UNION
			SELECT 
				jfb_post.post_id,
				jfb_user.jfb_user_id,
				jfb_user.jfb_user_name,
				jfb_user.jfb_user_facebook,
				jfb_user.jfb_user_twitter,
				jfb_concert.jfb_concert_id,
				jfb_concert.concert_name,
				jfb_post_type.media_type,
				jfb_post.timestamp,
				jfb_post.public,
				jfb_post.text,
				jfb_post.media
			FROM 
				jfb_check_in
			LEFT JOIN
				jfb_post
			ON
				jfb_post.jfb_concert_id = jfb_check_in.jfb_concert_id
			LEFT JOIN
				jfb_post_type
			ON
				jfb_post_type.id = jfb_post.jfb_media_type
			LEFT JOIN
				jfb_concert
			ON
				jfb_concert.jfb_concert_id = jfb_check_in.jfb_concert_id
			LEFT JOIN
				jfb_user
			ON
				jfb_user.jfb_user_id = jfb_post.jfb_user_id
			LEFT JOIN
				jfb_user_abbo
			ON
				jfb_user_abbo.jfb_user_id = jfb_post.jfb_user_id
			LEFT JOIN
				jfb_concert_to_artist
			ON
				jfb_concert_to_artist.jfb_artist_id = jfb_user_abbo.jfb_artist_id
			WHERE
				jfb_check_in.jfb_user_id = '". $user_id ."'
			ORDER BY
				timestamp DESC
		";
		$result = $this->connect->query($sql);
		$array = $this->constructor->result->fetchRows($result);
		foreach($array as $key => $value){
			$array[$key]['timestamp'] = $this->constructor->date->quickDateTime($value['timestamp']);
		}
		return $array;
	}

	public function checkin($data = null){
		if($this->getConcertUpdates($data)){
			$array = array(
					'concert' => 'false',
					'checkin' => 'false',
					'data' => 'false'
				);
			$concertId = $this->getConcertIdAtThisLocation($data);
			if(is_array($concertId)){
				$checkins = $this->getcheckins($concertId[0]['jfb_concert_id'], $data);
				if(is_array($checkins)){
					$array['concert'] = true;
					$array['checkin'] = true;
					$array['data'] = $concertId;
					return $array;
				}else{
					if($data['checkin'] == 'true'){
						if($this->checkUserIn($data, $concertId[0]['jfb_concert_id'])){
							$array['concert'] = true;
							$array['checkin'] = true;
							$array['data'] = $concertId;
							return $array;
						}
					}
					$array['concert'] = true;
					$array['data'] = $concertId;
					return $array;
				}
			}else{
				$array['data'] = $concertId;
				return $array;
			}
		}
	}
	
	public function post($data = null){
		if($data['posttype'] == 'text'){
			return $this->post_text($data);
		}elseif($data['posttype'] == 'video'){
			return $this->post_video($data);
		}elseif($data['posttype'] == 'audio'){
			return $this->post_audio($data);
		}elseif($data['posttype'] == 'photo'){
			return $this->post_photo($data);
		}else{
			return 0;
		}
	}

	private function post_text($data = null){
		if($data != null){
			$sql = "
				INSERT INTO jfb_post(
					jfb_user_id,
					jfb_concert_id,
					jfb_post_type,
					timestamp,
					public,
					content,
					deleted
				)
				VALUES(
					'".$data['fbuserid']."',
					'".$data['jfb_concert_id']."',
					'1',
					'".$data['timestamp']."',
					'1',
					'".$data['content']."',
					'0'
				)
			";
			$result = $this->connect->query($sql);
			if(is_int($this->constructor->result->execId($result))){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	private function post_video($data = null){
		if($data != null){

		}else{
			return false;
		}
	}

	private function post_audio($data = null){
		if($data != null){
		
		}else{
			return false;
		}
	}

	private function post_photo($data = null){
		if($data != null){
		
		}else{
			return false;
		}
	}

	private function getConcertUpdates($data = null){
		/* 
		if(lastConcertUpdate($data)){
			Haal data op
			- concert_id parsen
			- location aanwezig?
				- Checken of het al in db staat, wegschrijven indien nodig
				- toevoegen van content aan dat concert
				- performing artiesten opslaan in de koppeltabel
				- Artiesten in de db wegschrijven (naam en id)
		}
		return true;
		*/

/*
		print_r($data);
		$concerts = file_get_contents("http://api.songkick.com/api/3.0/events.json?apikey=6mxacKU1Q5Q7Vl3X&location=geo:53.021205,5.648687&min_date=2012-06-26&max_date=2012-07-30");
		$parsed = json_decode($concerts,true);	
		$array = $parsed['resultsPage']['results']['event'];
		$count = count($array);
		for($i=0;$i<$count;$i++){
			$array[$i];
		}
*/
		return true;
	}
	
	private function lastConcertUpdate($data){
		$time = $data['time'] - 18000;
		$sql = "
			SELECT
				id
			FROM
				jfb_concert_update
			WHERE
				location = '".$data['location']."'
			AND
				time > '".$time."'
		";
		$result = $this->connect->query($sql);
		if($this->constructor->result->countRows($result) == 1){
			return true;
		}else{
			return false;
		}
	}
	
	private function checkUserIn($data, $concertId){
		$sql = "
			INSERT INTO jfb_check_in(
				jfb_user_id,
				location,
				timestamp,
				jfb_concert_id
			)
			VALUES(
				'".$data['fbuserid']."',
				'".$data['location']."',
				'".$data['time']."',
				'".$concertId."'
			)
		";
		$result = $this->connect->query($sql);
		if(is_int($this->constructor->result->execId($result))){
			return true;
		}else{
			return false;
		}
	}

	private function getConcertIdAtThisLocation($data = null){
		$sql = "
			SELECT
				jfb_concert.jfb_concert_id,
				jfb_concert.concert_name,
				jfb_concert.place
			FROM
				jfb_concert
			WHERE
				jfb_concert.location = '".$data['location']."'
			AND 
				jfb_concert.starttime < '".$data['time']."'
			AND
				jfb_concert.endtime > '".$data['time']."'
		";
		$result = $this->connect->query($sql);
		if($this->constructor->result->countRows($result) == 1){
			return $this->constructor->result->fetchRows($result);
		}else{
			return 0;
		}
	}

	private function getcheckins($concertId, $data){
		$sql = "
			SELECT
				jfb_concert.jfb_concert_id,
				jfb_concert.concert_name,
				jfb_concert.place
			FROM
				jfb_check_in
			LEFT JOIN
				jfb_concert
			ON
				jfb_concert.jfb_concert_id = jfb_check_in.jfb_concert_id
			WHERE
				jfb_check_in.jfb_user_id = '".$data['fbuserid']."'
			AND
				jfb_check_in.jfb_concert_id = '".$concertId."'
		";
		$result = $this->connect->query($sql);
		if($this->constructor->result->countRows($result) == 1){
			return $this->constructor->result->fetchRows($result);
		}else{
			return 0;
		}
	}
}
?>