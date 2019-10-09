<?php

require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

function publishMessage($JSON)
{
	#cred: ip-address, port, username, password, vhost
	$connection = new AMQPStreamConnection('192.168.0.105', 5672, 'kevin', 'kevin', 'authentication');
	$channel = $connection->channel();
	$channel->queue_declare('hello', false, false, false, false);
	$msg = new AMQPMessage($JSON);
	$channel->basic_publish($msg, '', 'hello');
	
	echo " [x] Sent 'Hello World!'\n";
	$channel->close();
	$connection->close();
}
?>
