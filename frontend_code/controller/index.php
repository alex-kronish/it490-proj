<?php 
error_reporting (E_ALL); 
ini_set ('display_errors', 'on');
require_once '../rabbitmq/send.php';
require_once '../rabbitmq/receive.php';
require_once '../model/youtube/youtube-api.php';
require_once '../model/steam/steam-api.php';
require_once '../model/twitch/twitch-api.php';
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
	$api = new Steam_API($_SESSION['user']->getSteamID());
	//$api = new Steam_API('76561198100918883');
	$api->get_info(function($response){});
	$api->get_games_list(function($response){});
	$api->get_friends_list(function($response){});
	$_SESSION['steam-user'] = $api;
	include '../view/steam-id.php';
}

if($action == 'view-friend-page')
{
	$user = $_SESSION['steam-user'];
	$friend_steam_id = filter_input(INPUT_GET, 'steamid');
	$api = new Steam_API($friend_steam_id);
	$api->get_info(function($response)use($api){});
	$api->get_games_list(function($response) use($api){
		#delete this after testing with real api
		//$response = json_decode(file_get_contents('../data/friend-games.json'), true);
		//$api->json_recurse_games_list($response);
	});
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

if($action == 'view-twitch-search')
{
	include '../view/twitch-search.php';
}

if($action == 'stream-search')
{
	$terms = filter_input(INPUT_GET, 'search-terms');
	$api = new Twitch_API();
	$api->get_stream_results($terms, function($response){});
	include '../view/twitch-search.php';
}

if($action == 'sign-out')
{
	session_unset();
	header('Location: ../view/sign-in.php');
}


#Methods use for AJAX Calls
if($action == 'request-game-info')
{
	$appid = filter_input(INPUT_GET, 'app-id');
	$steamid = $_SESSION['user']->getSteamID();
	$api = new Steam_API($steamid);
	$api->get_game_info($appid, function($response){});
	$info = isset($api->get_game_info_array()['info'][0]['description']) ? $api->get_game_info_array()['info'][0]['description'] : "<p>No game information is available from Steam Store at this time</p>";
	$discount = isset($api->get_game_info_array()['info'][0]['discount']) ? $api->get_game_info_array()['info'][0]['discount'] : "false";
	$tags = $api->get_tags();
	if($discount == 'false')
		echo "
			<script type=\"text/javascript\">
				$('#discount-img').text('');
				$('#desc img').attr('class', 'img-fluid rounded mx-auto d-block');
			</script>
			<div id=\"desc\">".$info.$tags."</div>";
	else
		echo "
			<script type=\"text/javascript\">
				$('#desc img').attr('class', 'img-fluid rounded mx-auto d-block');
				$('#discount-img').text('').append('<img src=\"../view/images/discount.png\" class=\"rounded float-right\" width=\"30\" height=\"30\" alt=\"Game is on sale!\">');
			</script>
			<div id=\"desc\">".$info."</div>";
}

if($action == 'match-history')
{
	$user = filter_input(INPUT_GET, 'user');
	$friend = filter_input(INPUT_GET, 'friend');
	$outcome = filter_input(INPUT_GET, 'outcome');
	$num_matches = filter_input(INPUT_GET, 'num-matches');
	$data = array('operation' => 'match-history', 'current-user' => $user,'friend' => $friend, 'outcome' => $outcome, 'num-matches' => $num_matches);
	$api = new Steam_API();
	$api->update_match_history($data, function($response) use($api){
		echo $api->get_ratio();
	});
}

if($action == 'view-history')
{
	$user = filter_input(INPUT_GET, 'user');
	$friend = filter_input(INPUT_GET, 'friend');
	$api = new Steam_API();
	$data = array('operation' => 'view-history', 'current-user' => $user, 'friend' => $friend);
	$api->view_match_history($data, function($response){
		$ratio = 'Wins: '.$response['wins'].' Losses: '. $response['losses'];
		echo $ratio;
	});
}

if($action == 'leaderboard')
{
	$current_user = $_SESSION['steam-user'];
	$steam_id_user = $current_user->get_steam_id();
	$steam_id_friend = filter_input(INPUT_GET, 'friend-steam-id');
	$game_title = filter_input(INPUT_GET, 'game-title');
	$api = new Steam_API($steam_id_friend);
	$api->get_achievements($game_title, function($response) use($current_user, $game_title){
		if($response)
			$current_user->get_achievements($game_title, function($response) use($current_user){
				//$payload = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/achievements.json'), true);
				//$current_user->json_recurse_achievements($response);
			});
	});
	$achievement_list = $current_user->compare_achievements_array($current_user->get_achievements_array() ,$api->get_achievements_array());
	echo json_encode($achievement_list);
}


?>