<?php

class Steam_API
{
	private $STEAM_ID;
	private $API_KEY = '9DFE29EE9327E1BB13DC6F0C4CD3FEE3';
	private $RATIO;
	private $LEAST_PLAYED_GAMES=[];
	private $FRIEND_LIST=[];
	private $GAME_INFO=[];
	private $USER_INFO=[];
	private $MUTUAL_GAMES=[];
	private $ACHIEVEMENTS=[];

	public function __construct($STEAM_ID=0)
	{
		$this->STEAM_ID = $STEAM_ID;
	}

	public function get_steam_id()
	{
		return $this->STEAM_ID;
	}

/**************************** API Request: Getting Games List *******************************/

	/* RabbitMQ: Get games list with titles from Steam API */
	public function get_games_list($CALLBACK)
	{
		$data = array
		(
			'operation' => 'get-games-list',
			'steam-id' => $this->STEAM_ID,
			'api-key' => $this->API_KEY,
			'format' => 'json',
			'include_appinfo' => '1'
		);
		
		$data = json_encode($data);
		produceMessage($data, 'api', 'hello');
		consume('get-games-list', 'api_response', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/games.json'), true);
			if(!array_key_exists('error', $response))
				$this->json_recurse_games_list($response);
			else echo "<script type=text/javascript> alert('Games list is set private.'); </script>";
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $response);
		});
	}

	/* Return all owned games with 0 playtime minutes in an array */
	public function get_least_played_games()
	{
		return $this->LEAST_PLAYED_GAMES;
	}

	/* Store all owned games with 0 playtime minutes in an array */
	public function set_least_played_games($PAYLOAD)
	{
		unset($PAYLOAD['operation']);
		foreach($PAYLOAD as $KEY)
			if($KEY['playtime-forever'] == '0')
				array_push($this->LEAST_PLAYED_GAMES, $KEY);
		return $this->LEAST_PLAYED_GAMES;
	}

	/* Echo HTML string of owned games */
	public function echo_html_owned_games()
	{
		$i = 0;
		if(count($this->LEAST_PLAYED_GAMES) > 0)
			foreach($this->LEAST_PLAYED_GAMES as $KEY)
			{
				if($i % 2 == 0){
					#<!-- Anchor trigger modal -->
					echo
					"<a class=\"game\" id=\"game".$KEY['appid']."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\"><div class=\"row\"><h6 style=\"background-color: #ededed;\" class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
					$i++;
				}
				else
				{
					echo
					"<a class=\"game\" id=\"game".$KEY['appid']."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\"><div class=\"row\"><h6 class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
					$i++;
				}
			}
		else
			echo "No games available";
	}

	/* Compare title of game to array and check if that title exists */
	public function compare_title($TITLE, $LIST)
	{
		foreach((array)$LIST as $KEY)
			if($TITLE == $KEY['name'])
				return true;
		return false;
	}

	/* Recursively loop the json payload and store the information into another array for use */
	public function json_recurse_games_list($PAYLOAD)
	{
		$array = array();
		$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($PAYLOAD),RecursiveIteratorIterator::SELF_FIRST);
		foreach ($jsonIterator as $key => $val)
		    if(is_array($val) && array_key_exists('appid', $val)) 
		        array_push($array, array('appid' => $val['appid'], 'name' => $val['name']));
		$this->LEAST_PLAYED_GAMES = $array;
	}


/********************************* API Request: Friend/Friend List Functions ************************************/

	/* RabbitMQ: Get list of friends associated with current user on Steam */
	public function get_friends_list($CALLBACK)
	{
		$data = array 
		(
			'operation' => 'get-friends-list',
			'steam-id' => $this->STEAM_ID,
			'api-key' => $this->API_KEY,
		);

		$data = json_encode($data);
		produceMessage($data, 'api', 'hello');
		consume('get-friends-list', 'api_response', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/friend-list.json'), true);
			$this->json_recurse_friend_list($response);
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $response);
		});
	}

	/* Return friend list array */
	public function get_friends_array()
	{
		return $this->FRIEND_LIST;
	}

	/* Echo html of every game owned by friend and highlight mutually shared games with current user */
	public function echo_html_friend_owned_games($LIST)
	{
		if(count($this->LEAST_PLAYED_GAMES) > 0)
			foreach($this->LEAST_PLAYED_GAMES as $KEY)
			{
				if($this->compare_title($KEY['name'], $LIST)){
					#<!-- Anchor trigger modal -->
					array_push($this->MUTUAL_GAMES, array('name' => $KEY['name'], 'app-id' => $KEY['appid']));
					echo
					"<a class=\"game\" id=\"game".$KEY['appid']."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\">
						<h6 style=\"background-color: #ffd27f; border-bottom: 1px solid black;\">".$KEY['name']."</h6>
					</a>";	
				}
				else
				{
					echo
					"<a class=\"game\" id=\"game".$KEY['appid']."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\">
						<h6 style=\"border-bottom: 1px solid black;\">".$KEY['name']."</h6>
					</a>";
				}
			}
		else
			echo "No games available";
	}

	/* Echo HTML string of friends list */
	public function echo_html_friend_list()
	{
		$i = 0;
		if(count($this->FRIEND_LIST) > 0)
			foreach($this->FRIEND_LIST as $KEY)
			{
				if($i % 2 == 0){
					#<!-- Anchor trigger modal -->
					echo
					"<a id=\"game".$i."\" href=\"../controller/index.php?action=view-friend-page&steamid=".$KEY['steamid']."\"><div class=\"row\"><h6 style=\"background-color: #ededed;\" class=\"col-md-12\">".$KEY['personaname']."</h6></div></a>";
					$i++;
				}
				else
				{
					echo
					"<a id=\"game".$i."\" href=\"../controller/index.php?action=view-friend-page&steamid=".$KEY['steamid']."\"><div class=\"row\"><h6 class=\"col-md-12\">".$KEY['personaname']."</h6></div></a>";
					$i++;
				}
			}
		else
			echo "No friends available";
	}

	/* Recursively loop the json payload and store the information into another array for use */
	public function json_recurse_friend_list($PAYLOAD)
	{
		$array = array();
		$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($PAYLOAD),RecursiveIteratorIterator::SELF_FIRST);
		foreach ($jsonIterator as $key => $val)
		    if(is_array($val) && array_key_exists('steamid', $val)) 
		        array_push($array, array('steamid' => $val['steamid'], 'personaname' => $val['personaname']));
		$this->FRIEND_LIST = $array;
	}

	/* Return array of mutally shared games */
	public function get_mutal_games_array()
	{
		if(count($this->MUTUAL_GAMES) > 0)
			return $this->MUTUAL_GAMES;
		else
			return 'No mutual games found';
	}

	/* RabbitMQ: Retrieve user info of their Steam profile  */
	public function get_info($CALLBACK)
	{
		$data = array
		(
			'operation' => 'get-steam-info',
			'api-key' => $this->API_KEY,
			'steam-id' => $this->STEAM_ID
		);
		$data = json_encode($data);
		produceMessage($data, 'api', 'hello');
		consume('get-steam-info', 'api_response', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/user-info.json'), true);
			$this->json_recurse_user_info($response);
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $response);
		});
	}

	/* Recursively loop the json payload and store the user info into another array for use */
	public function json_recurse_user_info($PAYLOAD)
	{
		$array = array();
		$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($PAYLOAD),RecursiveIteratorIterator::SELF_FIRST);
		foreach ($jsonIterator as $key => $val)
		    if(is_array($val) && array_key_exists('timecreated', $val)) 
		        array_push($array, array(
		        	'steamid' => $val['steamid'], 
		        	'personaname' => $val['personaname'], 
		        	'profileurl' => $val['profileurl'], 
		        	'avatar' => $val['avatar']
		        )
			);
		$this->USER_INFO = $array;
	}

	/* Return the user's Steam Account Info */
	public function get_user_info_array()
	{
		return $this->USER_INFO;
	}

/********************************* API Request: Game Discounts/Tags Functions ************************************/
	
	/* RabbitMQ: Retrieve game information from Steam Store API */
	public function get_game_info($APPID, $CALLBACK)
	{
		$data = array 
		(
			'operation' => 'get-game-info',
			'app-id' => $APPID, 			
		);
		$data = json_encode($data);
		produceMessage($data, 'api', 'hello');
		consume('get-game-info', 'api_response', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/game-discounts.json'), true);
			$this->json_recurse_game_info($response);
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $response);
		});
	}

	/* Recursively loop the json payload and store the game info into another array for use */
	public function json_recurse_game_info($PAYLOAD)
	{
		$array = array('tags' => array(), 'developers' => array(), 'info' => array());
		$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($PAYLOAD),RecursiveIteratorIterator::SELF_FIRST);
		foreach ($jsonIterator as $key => $val)
		    if(is_array($val) && array_key_exists('price_overview', $val)){
		    	array_push($array['info'], array(
		    		'discount' => (strval($val['price_overview']['discount_percent']) == 0 ? 'false' : 'true'),
		    		'description' => $val['detailed_description'],
		    		'achievement-count' => strval($val['achievements']['total'])
		    	));
		    	foreach($val['categories'] as $key2 => $val2)
		    		array_push($array['tags'], $val2['description']);
		    	foreach($val['developers'] as $key3)
		    		array_push($array['developers'], $key3);
		    }
		$this->GAME_INFO = $array;
	}

	/* Return array of game info */
	public function get_game_info_array()
	{
		return $this->GAME_INFO;
	}

	/* Concatenate Tags together in HTML */
	public function get_tags()
	{
		$tags_html = "<b><p style=\"color: #6441A5;\">Tags:</p></b><span class=\"pb-3\">";
		foreach($this->get_game_info_array()['tags'] as $key => $value)
		{
			$tags_html = $tags_html."<a href=\"../controller/index.php?action=search&search-terms=".$value."\">".$value."&nbsp;</a>";
		}
		$tags_html = $tags_html."</span>";
		return $tags_html;
	}

/********************************* API Request: Leaderboard Game Achievements ************************************/
	
	/* RabbitMQ: Get user's achievements based on app-id */
	public function get_achievements($APPID, $CALLBACK)
	{
		$data = array 
		(
			'current-user-id' => $this->STEAM_ID,
			'operation' => 'leaderboard',
			'app-id' => $APPID
		);
		$data = json_encode($data);
		produceMessage($data, 'api', 'hello');
		consume('leaderboard', 'api_response', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/friend-achievements.json'), true);
			$response = $this->json_recurse_achievements($response);
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $response);
		});
	}

	/* Parse JSON for the apiname, and achieved status */
	public function json_recurse_achievements($PAYLOAD)
	{
		$array=[];
		$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($PAYLOAD),RecursiveIteratorIterator::SELF_FIRST);
		foreach ($jsonIterator as $key => $val){
		    if(is_array($val) && array_key_exists('apiname', $val))
		    	array_push($array, array('name' => $val['name'], 'achieved' => strval($val['achieved']), 'description' => $val['description']));
		    elseif(is_array($val) && array_key_exists('error', $val)){
		    	return false;
		    }
		}
		$this->ACHIEVEMENTS = $array;
		return true;
	}

	/* Return results (if true) */
	public function get_achievements_array()
	{
		return $this->ACHIEVEMENTS;
	}

	/* Return HTML for each achievement and compare between two players */
	public function compare_achievements_array($P1, $P2)
	{
		$array = [];
		if(count($P1) == count($P2)){
			for($i = 0; $i < count($P1); $i++){
				$p1 = $P1[$i]['achieved'] == '1' ? '<td class="text-center" style="color: green;"><b>&#10003;</b></td>' : '<td class="text-center" style="color: red;"><b>&times;</b></td>';
				$p2 = $P2[$i]['achieved'] == '1' ? '<td class="text-center" style="color: green;"><b>&#10003;</b></td>' : '<td class="text-center" style="color: red;"><b>&times;</b></td>';
				$html = "<tr style=\"border-bottom: 1px solid black;\"><td>".$P1[$i]['name']."</td>".$p1.$p2."</tr>";
				array_push($array, $html);
			}
			return $array;
		}

		else
			echo "<script type=\"text/javascript\">alert('Achievement comparison error!');</script>";	
	}

/********************************* RabbitMQ Message: Match History ************************************/
	public function update_match_history($PAYLOAD, $CALLBACK)
	{
		$data = json_encode($PAYLOAD);
		produceMessage($data, 'match_history', 'hello');
		consume('match-history', 'match_results', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/match-history.json'), true);
			//$response['result'] == true/false
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $response);
		});
	}

	public function get_ratio()
	{
		return $this->RATIO;
	}

	public function view_match_history($PAYLOAD, $CALLBACK)
	{
		$data = json_encode($PAYLOAD);
		produceMessage($data, 'match_history', 'hello');
		consume('view-history', 'match_results', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/match-history.json'), true);
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $response);
		});
	}
}

?>