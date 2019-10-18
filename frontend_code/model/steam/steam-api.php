<?php

class Steam_API
{
	private $STEAM_ID;
	private $API_KEY = 'F7289659E0D7172AAEEB93CF512CC810';
	private $LEAST_PLAYED_GAMES=[];

	public function __construct($STEAM_ID)
	{
		$this->STEAM_ID = $STEAM_ID;
	}

	/* Get games list with titles from Steam API */
	public function get_games_list($CALLBACK)
	{
		/*
		$data = array
		(
			'operation' => 'get-games-list',
			'steam-id' => $this->STEAM_ID,
			'api-key' => $this->API_KEY,
			'format' => 'json',
			'include_appinfo' => '1'
		);*/

		$data = array (
			'operation' => 'get-games-list',
			'game1' => array
			(
				'appid' => '10',
				'name' => 'Counter-Strike',
				'playtime-forever' => '0'
			),
			'game2' => array
			(
				'appid' => '101',
				'name' => 'Left 4 Dead 2',
				'playtime-forever' => '0'
			),
			'game3' => array
			(
				'appid' => '102',
				'name' => 'Half-Life 2',
				'playtime-forever' => '0'
			),

			'game4' => array
			(
				'appid' => '102',
				'name' => 'Bioshock',
				'playtime-forever' => '0'
			),

			'game5' => array
			(
				'appid' => '102',
				'name' => 'Mass Effect 3',
				'playtime-forever' => '0'
			),

			'game6' => array
			(
				'appid' => '102',
				'name' => 'Hollow Knight',
				'playtime-forever' => '0'
			)
		);

		
		$data = json_encode($data);
		produceMessage($data, 'steam-api', 'hello');
		
		consume('get-games-list', 'steam-api', 'hello', function($response, $channel, $connection) use($CALLBACK){
			$this->set_least_played_games($response);
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

	public function get_friends_list($CALLBACK)
	{
		$data = array 
		(
			'operation' => 'get-friends-list',
			'steam-id' => $this->STEAM_ID,
			'api-key' => $this->API_KEY,
			'format' => 'json',
			'relationship' => 'friend'
		);
		$data = json_encode($data);
		produceMessage($data, 'steam-api', 'hello');
		consume('get-friends-list', 'steam-api', 'hello', function($response, $channel, $connection) use($CALLBACK){
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $response);
		});
	}

	/* Retrieve user info of their Steam profile  */
	public function get_info($CALLBACK)
	{

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
					"<a id=\"game".$i."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\"><div class=\"row\"><h6 style=\"background-color: #ededed;\" class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
					$i++;
				}
				else
				{
					echo
					"<a id=\"game".$i."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\"><div class=\"row\"><h6 class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
					$i++;
				}
			}
		else
			echo "No games available";
	}
}

?>