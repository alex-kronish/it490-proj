<?php

class Steam_API
{
	private $STEAM_ID;
	private $API_KEY = '9DFE29EE9327E1BB13DC6F0C4CD3FEE3';
	private $LEAST_PLAYED_GAMES=[];
	private $FRIEND_LIST=[];

	public function __construct($STEAM_ID)
	{
		$this->STEAM_ID = $STEAM_ID;
	}

	/* RabbitMQ: Get games list with titles from Steam API */
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
			array
			(
				'appid' => '10',
				'name' => 'Counter-Strike',
				'playtime-forever' => '0'
			),
			array
			(
				'appid' => '101',
				'name' => 'Left 4 Dead 2',
				'playtime-forever' => '0'
			),
			array
			(
				'appid' => '102',
				'name' => 'Half-Life 2',
				'playtime-forever' => '0'
			),

			array
			(
				'appid' => '102',
				'name' => 'Bioshock',
				'playtime-forever' => '0'
			),

			array
			(
				'appid' => '102',
				'name' => 'Mass Effect 3',
				'playtime-forever' => '0'
			),

			array
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

	/* Store all friends in list */
	public function set_friends_array($PAYLOAD)
	{
		unset($PAYLOAD['operation']);
		foreach($PAYLOAD['friends'] as $KEY)
			array_push($this->FRIEND_LIST, $KEY);
	}

	/* Return friend list array */
	public function get_friends_array()
	{
		return $this->FRIEND_LIST;
	}

	/* RabbitMQ: Get list of friends associated with current user on Steam */
	public function get_friends_list($CALLBACK)
	{
		/*
		$data = array 
		(
			'operation' => 'get-friends-list',
			'steam-id' => $this->STEAM_ID,
			'api-key' => $this->API_KEY,
			'format' => 'json',
			'relationship' => 'friend'
		);
		*/

		$data = array (
			'operation' => 'get-friends-list',
			'friends' => array
			(
				array(
					'steam-id' => '123456',
					'name' => 'solorzke',
				),

				array(
					'steam-id' => '123456',
					'name' => 'solorzke',
				),

				array(
					'steam-id' => '123456',
					'name' => 'solorzke',
				),

				array(
					'steam-id' => '123456',
					'name' => 'solorzke',
				),

				array(
					'steam-id' => '123456',
					'name' => 'solorzke',
				),

				array(
					'steam-id' => '123456',
					'name' => 'solorzke',
				)
			)
		);
		$data = json_encode($data);
		produceMessage($data, 'steam-api', 'hello');
		consume('get-friends-list', 'steam-api', 'hello', function($response, $channel, $connection) use($CALLBACK){
			$this->set_friends_array($response);
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
					"<a class=\"game\" id=\"game".$i."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\"><div class=\"row\"><h6 style=\"background-color: #ededed;\" class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
					$i++;
				}
				else
				{
					echo
					"<a class=\"game\" id=\"game".$i."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\"><div class=\"row\"><h6 class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
					$i++;
				}
			}
		else
			echo "No games available";
	}

	public function echo_html_friend_owned_games($LIST)
	{
		$i=0;
		if(count($this->LEAST_PLAYED_GAMES) > 0)
			foreach($this->LEAST_PLAYED_GAMES as $KEY)
			{
				if($this->compare_title($KEY['name'], $LIST)){
					#<!-- Anchor trigger modal -->
					echo
					"<a id=\"game".$i."\" data-toggle=\"modal\" data-target=\"#gamemodal\" href=\"#\"><div class=\"row\"><h6 style=\"background-color: cyan;\" class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
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
					"<a id=\"game".$i."\" href=\"../controller/index.php?action=view-friend-page&steamid=".$KEY['steam-id']."\"><div class=\"row\"><h6 style=\"background-color: #ededed;\" class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
					$i++;
				}
				else
				{
					echo
					"<a id=\"game".$i."\" href=\"../controller/index.php?action=view-friend-page&steamid=".$KEY['steam-id']."\"><div class=\"row\"><h6 class=\"col-md-12\">".$KEY['name']."</h6></div></a>";
					$i++;
				}
			}
		else
			echo "No friends available";
	}

	public function compare_title($TITLE, $LIST)
	{
		foreach($LIST as $KEY)
			if($TITLE == $KEY['name'])
				return true;
		return false;
	}
}

?>