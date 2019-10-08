<?php 
require ('authentication.php');

$action = filter_input(INPUT_POST, 'action');
if(!isset($action) || $action !== 'sign-in')
	header('Location: ../view/sign-in.php');


if(isset($_POST['user-name']) && isset($_POST['password']))
{
	$USER_NAME = filter_input(INPUT_POST, 'user-name');
	$PASSWORD = filter_input(INPUT_POST, 'password');
	$auth = new Authentication();
	$result = ($auth->validate($USER_NAME, $PASSWORD)) ? 'true' : 'false';

	/* Testing with RabbitMQ, verify login info here */
	/* $response = $auth->verify($USER_NAME, $PASSWORD); 
	   
	   if($response)
	   {
			#User logs into the site and has permission to access
	   }
	   else
	   		header('Location: ../view/sign-in.php');

	*/

	#password_verify($PASSWORD, <HASHED_PASSWORD_FROM_DB>);
	
	/*
	$python = `python ../test.py`;
	echo $python;
	*/

	if($result == 'true')
	{
		$auth->verify($USER_NAME, $PASSWORD);
	}
}

else
{
	header('Location: ../view/sign-in.php');
}
?>