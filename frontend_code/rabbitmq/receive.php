<?php

require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

function consumeMessage($OPERATION, $CALLBACK)
{
	#cred: ip-address, port, username, password, vhost
	$connection = new AMQPStreamConnection('192.168.0.105', 5672, 'kevin', 'kevin', 'authentication_results');
	$channel = $connection->channel();
	$channel->queue_declare('hello', false, false, false, false);

	$parameters = [$OPERATION, $CALLBACK, $channel, $connection];

	echo " [*] Waiting for messages. To exit press CTRL+C\n";

	$callback = function ($msg) use ($parameters) {
		$payload = json_decode($msg->body, true);
		if($payload['operation'] == $parameters[0])
			if(is_callable($parameters[1])){
				call_user_func($parameters[1], $payload, $parameters[2], $parameters[3]);
			}
	};

	$channel->basic_consume('hello', '', false, true, false, false, $callback);
	while ($channel->is_consuming()) {
			$channel->wait();
	}

	$channel->close();
	$connection->close();
}

/* Do not git commit code below until you've tested it properly. */
function consume($OPERATION, $VHOST, $QUEUE, $CALLBACK)
{
	#cred: ip-address, port, username, password, vhost
	$connection = new AMQPStreamConnection('192.168.0.105', 5672, 'kevin', 'kevin', $VHOST);
	$channel = $connection->channel();
	$channel->queue_declare($QUEUE, false, false, false, false);

	$parameters = [$OPERATION, $CALLBACK, $channel, $connection];

	echo " [*] Waiting for messages. To exit press CTRL+C\n";

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
/*
$api = new YouTube_API();
$a = $api->consume_api_request(function($results){
	print_r($results);
});

consume('search-results', 'youtube', 'hello', function($response, $channel, $connection){

	print_r($response);
	$channel->close();
	$connection->close();
}); */

?>	
