<?php

require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

function publishMessage($JSON)
{
	$connection = new AMQPStreamConnection('localhost', 5672, 'kas58', 'password');
	$channel = $connection->channel();
	$channel->queue_declare('hello', false, false, false, false);
	$msg = new AMQPMessage($JSON);
	$channel->basic_publish($msg, '', 'hello');
	
	echo " [x] Sent 'Hello World!'\n";
	$channel->close();
	$connection->close();
}
?>
