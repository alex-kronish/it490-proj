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

/* Do not git commit code below until you've tested it properly. */
function produceMessage($JSON, $VHOST, $QUEUE)
{
	#cred: ip-address, port, username, password, vhost
	$connection = new AMQPStreamConnection('192.168.0.105', 5672, 'kevin', 'kevin', $VHOST);
	$channel = $connection->channel();
	$channel->queue_declare($QUEUE, false, false, false, false);
	$msg = new AMQPMessage($JSON);
	$channel->basic_publish($msg, '', $QUEUE);
	
	echo " [x] Sent 'Hello World!'\n";
	$channel->close();
	$connection->close();
}

/*

$api = new YouTube_API();
$api->produce_api_request(array('marvel', 'avengers')); 



$data = array
	(
		'operation' => 'search-results',
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
	$data = json_encode($data);
produceMessage($data, 'youtube', 'hello');

*/

?>
