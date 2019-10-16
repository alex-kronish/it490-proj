<?php 
/* 
~ Validate funx: true if validation is correct, false if illegal condition is met 

*/
require ('../rabbitmq/send.php');
require ('../rabbitmq/receive.php');
require ('user.php');
session_start();

class Authentication
{
	private $USER_NAME;
	private $PASSWORD;
	private $EMAIL;
	private $STEAM_ID;

	public function __construct()
	{}

	public function getUsername()
	{
		return $this->USER_NAME;
	}

	public function getPassword()
	{
		return $this->PASSWORD;
	}

	public function getEmail()
	{
		return $this->EMAIL;
	}

	public function validate($USER_NAME, $PASSWORD, $EMAIL=0, $STEAM_ID=0)
	{
		$username = array
		(
			'validateEmpty' => $this->validateEmpty($USER_NAME),
			'validateNumberOfChars' => $this->validateNumberOfChars($USER_NAME),
			'validateSpecialChars' => $this->validateSpecialChars($USER_NAME),
			'validateNumberStarts' => $this->validateNumberStarts($USER_NAME)
		);

		$password = array
		(
			'validateEmpty' => $this->validateEmpty($PASSWORD), 
			'validateNumberOfChars' => $this->validateNumberOfChars($PASSWORD),
			'validateSpecialChars' => $this->validateSpecialChars($PASSWORD), 
			'validateNumberStarts' => $this->validateNumberStarts($PASSWORD) 
		);

		if($EMAIL != 0 && $STEAM_ID != 0)
		{
			$email = array
			(
				'validateEmpty' => $this->validateEmpty($EMAIL),
				'validateNumberOfChars' => $this->validateNumberOfChars($EMAIL),
				'validateSpecialChars' => $this->validateSpecialChars($EMAIL),
				'validateNumberStarts' => $this->validateNumberStarts($USER_NAME)
			);

			$steam_id = array 
			(
				'validateEmpty' => $this->validateEmpty($STEAM_ID),
				'validateNumberOfChars' => $this->validateNumberOfChars($STEAM_ID),
				'validateSpecialChars' => $this->validateSpecialChars($STEAM_ID),
				'validateNumberStarts' => $this->validateNumberStarts($STEAM_ID)
			);

			foreach($email as $key => $value)
			{
				if($value == false)
					return false;
			}

			foreach($steam_id as $key => $value)
			{
				if($value == false)
					return false;
			}
		}

		foreach($username as $key => $value)
		{
			if($value == false)
				return false;
		}

		foreach($password as $key => $value)
		{
			if($value == false)
				return false;
		}

		return true;
	}

	public function verify($USER_NAME, $PASSWORD)
	{
		/* Send the username and password to rabbit mq queue stack, and listen for a response from the server */

		$data = array (
			'operation' => 'login',
			'username' => $USER_NAME,
			'password' => $PASSWORD
		);
		$payload = json_encode($data);
		publishMessage($payload);
		consume('login', 'authentication_results', 'hello', function($response, $channel, $connection) use ($data){
			if($response['result'] == 'True'){
				$channel->close();
				$connection->close();
				$user = new User($data['username'], '0', $response['steam-id']);
				$_SESSION['user'] = $user;
				header('Location: ../controller/index.php?action=view-home');
			}
		});
	}

	public function register($USER_NAME, $PASSWORD, $EMAIL, $STEAM_ID)
	{
		$data = array(
			'operation' => 'register',
			'username' => $USER_NAME,
			'password' => $PASSWORD,
			'email' => $EMAIL,
			'steam-id' => $STEAM_ID
		);
		$payload = json_encode($data);
		produceMessage($payload, 'authentication', 'hello');
		consumeMessage('register', function($response, $channel, $connection) use ($data){	

			if($response['result'] == 'True'){
				$channel->close();
				$connection->close();
				$user = new User($data['username'], $data['email'], $data['steam-id']);
				$_SESSION['user'] = $user;
				header('Location: ../controller/index.php?action=view-home');
			}
		});
		//$result = consumeMessage('register');
		//return $result['email'].' is your email address';
		
	}

	public function validateEmpty($DATA)
	{
		if(strlen($DATA) == 0)
			return false;
		else
			return true;
	}

	public function validateNumberOfChars($DATA)
	{
		if(strlen($DATA) >= 4)
			return true;

		else
			return false;
	}

	public function validateSpecialChars($DATA)
	{
		$pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
		if(!preg_match($pattern, $DATA))
			return true;
		else
			return false;
	} 

	public function validateNumberStarts($DATA)
	{
		if(is_numeric($DATA[0]))
			return false;

		elseif(is_numeric($DATA))
			return false;

		else
			return true;
	}
}


?>