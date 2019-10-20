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
	$api = new Steam_API('76561198100918883');
	$api->get_games_list(function($response){});
	$api->get_friends_list(function($response){});
	$_SESSION['steam-user'] = $api;
	include '../view/steam-id.php';
}

if($action == 'view-friend-page')
{
	//$user = filter_var($_SESSION['steam-user']);
	$friend_steam_id = filter_input(INPUT_GET, 'steamid');
	$api = new Steam_API($friend_steam_id);
	$api->get_games_list(function($response){});
	$data = array (
	array
	(
		'appid' => '10',
		'name' => 'Counter-Strike',
		'playtime-forever' => '0'
	),
	array
	(
		'appid' => '101',
		'name' => 'Left 4 Dead 2',
		'playtime-forever' => '0'
	),
	array
	(
		'appid' => '102',
		'name' => 'Half-Life 2',
		'playtime-forever' => '0'
	),

	array
	(
		'appid' => '102',
		'name' => 'Bioshock',
		'playtime-forever' => '0'
	),

	array
	(
		'appid' => '102',
		'name' => 'Mass Effect 3',
		'playtime-forever' => '0'
	),

	array
	(
		'appid' => '102',
		'name' => 'Hollow Knight',
		'playtime-forever' => '0'
	)
);
	include '../view/friend-page.php';
}

if($action == 'search')
{
	$search_terms = filter_input(INPUT_GET, 'search-terms');
	$terms = explode(' ', $search_terms);
	$yt_api = new YouTube_API();
	$yt_api->get_search_results($terms, function($response){});
	include '../view/youtube-search.php';
}

?>