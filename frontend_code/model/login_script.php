<?php 
require ('authentication.php');

echo "Hello world";
$action = filter_input(INPUT_POST, 'action');
if(!isset($action) || $action !== 'sign-in')
	header('Location: ../view/sign-in.php');


if(isset($_POST['user-name']) && isset($_POST['password']))
{
	
	$USER_NAME = filter_input(INPUT_POST, 'user-name');
	$PASSWORD = filter_input(INPUT_POST, 'password');

	$auth = new Authentication();
	//$result = ($auth->validate($USER_NAME, $PASSWORD)) ? 'true' : 'false';
	$passH = md5($PASSWORD);
	$auth->verify($USER_NAME, $passH);
}

else
{
	header('Location: ../view/sign-in.php');
}
?>