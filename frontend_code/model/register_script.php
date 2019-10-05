<?php
require ('authentication.php');

$action = filter_input(INPUT_POST, 'action');
if(!isset($action) || $action !== 'register')
	header('Location: ../view/register.php');

if(isset($_POST['user-name']) && isset($_POST['password']) && isset($_POST['email']))
{
	$USER_NAME = filter_input(INPUT_POST, 'user-name');
	$PASSWORD = filter_input(INPUT_POST, 'password');
	$EMAIL = filter_input(INPUT_POST, 'email');
	$auth = new Authentication();
	$result = ($auth->validate($USER_NAME, $PASSWORD, $EMAIL)) ? 'true' : 'false';

	$passH = password_hash($PASSWORD, PASSWORD_BCRYPT);

	if($result == 'true')
	{
		echo "Hello world";
	}

	else 
		echo "Validate failed";

	/* Testing with RabbitMQ, verify login info here */
	/* $response = $auth->verify($USER_NAME, $PASSWORD); 
	   
	   if($response)
	   {
			#User logs into the site and has permission to access
	   }
	   else
	   		header('Location: ../view/sign-in.php');

	*/
}

?>