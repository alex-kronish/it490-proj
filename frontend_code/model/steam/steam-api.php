<?php

require_once '../rabbitmq/send.php';
require_once '../rabbitmq/receive.php';

class Steam_API
{
	private $STEAM_ID;
	private $VANITY_URL;
	private $API_KEY = 'F7289659E0D7172AAEEB93CF512CC810';

	public function __construct($STEAM_VANITY)
	{
		$this->STEAM_VANITY = $STEAM_VANITY;
	}

	public function getSteamID()
	{
		$data = array 
		(
			'operation' => 'get-steam-id',
			'vanity-url' => $this->VANITY_URL,
			'api-key' => $this->$API_KEY
		);

		$json = json_encode($data);
		produceMessage($json, 'steam-api', 'steam-api');
		$result = consume('get-steam-id', 'steam-api', 'steam-api' function($response, $channel, $connection) use($this->STEAM_ID){
			$channel->close();
			$connection->close();
			$this->STEAM_ID = $response['steam-id'];
		});
	}
}


?>