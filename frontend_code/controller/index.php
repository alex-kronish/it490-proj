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

if($action == 'view-youtube-search')
{
	include '../view/youtube-search.php';
}

if($action == 'view-home')
{
	include '../view/home.php';
}

?>