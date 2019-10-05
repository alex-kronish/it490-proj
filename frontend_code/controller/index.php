<?php 
session_start();

$action = filter_input(INPUT_POST, 'action');
if(!isset($action))
{
	$action = filter_input(INPUT_GET, 'action');
	if(!isset($action))
	{
		header('Location: ../view/sign-in.php');
	}
}

?>