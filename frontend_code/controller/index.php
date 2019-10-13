<?php 
error_reporting (E_ALL); 
ini_set ('display_errors', 'on');
session_start();
require_once '../model/youtube/youtube-api.php';

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

if($action == 'search')
{
	$search_terms = filter_input(INPUT_POST, 'search-terms');
	$terms = explode(' ', $search_terms);
	$yt_api = new YouTube_API();
	#produce API request
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