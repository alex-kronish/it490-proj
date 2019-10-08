<?php

require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

function consumeMessage($OPERATION, $CALLBACK)
{
	$connection = new AMQPStreamConnection('localhost', 5672, 'kas58', 'password');
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

/*
consumeMessage('register', function($payload){
	print_r($payload);
	//header('Location ../model/register_script.php?success=registration%20is%20verified');
});
*/

?>	
