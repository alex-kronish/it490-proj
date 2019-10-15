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
	}

	public function getUsername()
	{
		return $this->USERNAME;
	}

	public function setUsername($USERNAME)
	{
		$this->USERNAME = $USERNAME;
	}

	public function getClientID()
	{
		return $this->CLIENT_ID;
	}

	public function setClientID($CLIENT_ID)
	{
		$this->CLIENT_ID = $CLIENT_ID;
	}

	public function getSteamID()
	{
		return $this->STEAM_ID;
	}

	public function setSteamID($STEAM_ID)
	{
		$this->STEAM_ID = $STEAM_ID;
	}

	public function getEmail()
	{
		return $this->EMAIL;
	}

	public function setEmail($EMAIL)
	{
		$this->EMAIL = $EMAIL;
	}
}

?>