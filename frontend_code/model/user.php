<?php

class User
{
	private $USERNAME;
	private $CLIENT_ID; #TWITCH-API
	private $STEAM_ID;	#STEAM-API
	private $EMAIL;

	public function __construct($DATA)
	{
		$this->USERNAME = $DATA['username'];
		$this->CLIENT_ID = $DATA['client-id'];
		$this->STEAM_ID = $DATA['steam-id'];
		$this->EMAIL = $DATA['email'];
	}

	public function getUsername()
	{
		return $this->USERNAME;
	}

	public function getClientID()
	{
		return $this->CLIENT_ID;
	}

	public function getSteamID()
	{
		return $this->STEAM_ID;
	}

	public function getEmail()
	{
		return $this->EMAIL;
	}
}

?>