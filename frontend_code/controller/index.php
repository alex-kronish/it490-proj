<?php 
error_reporting (E_ALL); 
ini_set ('display_errors', 'on');
require_once '../rabbitmq/send.php';
require_once '../rabbitmq/receive.php';
require_once '../model/youtube/youtube-api.php';
require_once '../model/steam/steam-api.php';
require_once '../model/user.php';
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

if($action == 'view-steam-id')
{
	//$api = new Steam_API($_SESSION['user']->getSteamID());
	$api = new Steam_API('123456');
	$api->get_games_list(function($response){});
	include '../view/steam-id.php';
}

if($action == 'search')
{
	$search_terms = filter_input(INPUT_POST, 'search-terms');
	$terms = explode(' ', $search_terms);
	$yt_api = new YouTube_API();
	#$yt_api->produce_api_request($terms);
	/*
	$yt_api->consume_api_request(function($results){
		
	});
	*/
	#consume API request
	$data = array
	(
		'video-1' => array
		(
			'video-id' => 'LUaj6MrfBsU',
			'title' => 'The END OF FORTNITE!',
			'thumbnail' => 'https://i.ytimg.com/vi/LUaj6MrfBsU/default.jpg'
		),

		'video-2' => array
		(
			'video-id' => 'LUaj6MrfBsU',
			'title' => 'The END OF FORTNITE!',
			'thumbnail' => 'https://i.ytimg.com/vi/LUaj6MrfBsU/default.jpg'
		),

		'video-3' => array
		(
			'video-id' => 'LUaj6MrfBsU',
			'title' => 'The END OF FORTNITE!',
			'thumbnail' => 'https://i.ytimg.com/vi/LUaj6MrfBsU/default.jpg'
		),

		'video-4' => array
		(
			'video-id' => 'LUaj6MrfBsU',
			'title' => 'The END OF FORTNITE!',
			'thumbnail' => 'https://i.ytimg.com/vi/LUaj6MrfBsU/default.jpg'
		)
	);
	include '../view/youtube-search.php';
}

?>