<?php

require_once '../../vendor/autoload.php';
require_once 'send.php';
require_once '/var/www/html/it490-proj/frontend_code/model/youtube/youtube-api.php';
require_once '/var/www/html/it490-proj/frontend_code/model/steam/steam-api.php';
require_once '/var/www/html/it490-proj/frontend_code/model/twitch/twitch-api.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

/* Do not git commit code below until you've tested it properly. */
function consume($OPERATION, $VHOST, $QUEUE, $CALLBACK)
{
	#cred: ip-address, port, username, password, vhost
	$connection = new AMQPStreamConnection('192.168.0.105', 5672, 'kevin', 'kevin', $VHOST);
	$channel = $connection->channel();
	$channel->queue_declare($QUEUE, false, false, false, false);

	$parameters = [$OPERATION, $CALLBACK, $channel, $connection];
	echo "[*] Waiting for messages";
	$callback = function ($msg) use ($parameters) {
		$payload = json_decode($msg->body, true);
		if($payload['operation'] == $parameters[0])
			if(is_callable($parameters[1])){
				call_user_func($parameters[1], $payload, $parameters[2], $parameters[3]);
			}
	};

	$channel->basic_consume($QUEUE, '', false, true, false, false, $callback);
	
	while ($channel->is_consuming()) {
			$channel->wait();
	}

	$channel->close();
	$connection->close();
}


/* For local testing only 
function consume($OPERATION, $VHOST, $QUEUE, $CALLBACK)
{
	#cred: ip-address, port, username, password, vhost
	$connection = new AMQPStreamConnection('localhost', 5672, 'kevin', 'kevin', $VHOST);
	$channel = $connection->channel();
	$channel->queue_declare($QUEUE, false, false, false, false);

	$parameters = [$OPERATION, $CALLBACK, $channel, $connection];

	$callback = function ($msg) use ($parameters) {
		$payload = json_decode($msg->body, true);
		if($payload['operation'] == $parameters[0])
			if(is_callable($parameters[1])){
				call_user_func($parameters[1], $payload, $parameters[2], $parameters[3]);
			}
	};

	$channel->basic_consume($QUEUE, '', false, true, false, false, $callback);
	
	while ($channel->is_consuming()) {
			$channel->wait();
	}

	$channel->close();
	$connection->close();
} */


$youtube = new YouTube_API();
$youtube->get_search_results(array('information', 'technology'), function($response) use($youtube){
	print_r($youtube->get_search_results_array());
});
/*
$twitch = new Twitch_API();
$twitch->get_stream_results('Super Mario 64', function($response) use($twitch){
	print_r($twitch->get_stream_results_array());
});


$steam = new Steam_API('123124124123');
$steam->get_games_list(function($response)use($steam){
	print_r($steam->get_least_played_games());
});

$steam->get_friends_list(function($response) use($steam){
	print_r($steam->get_friends_array());
});

$steam = new Steam_API('123124124123');
$steam->get_info(function($response) use($steam){
	print_r($steam->get_user_info_array());
});

$steam = new Steam_API('123124124123');
$steam->get_achievements('12345', function($response) use($steam){
	print_r($steam->get_achievements_array());
});
*/
?>	
