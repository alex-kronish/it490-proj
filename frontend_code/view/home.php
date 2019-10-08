<!DOCTYPE html>
<html>
<head>
	<title>IT490-Project | Home</title>
</head>
<body>

	<?php 
		if(isset($_GET['success'])){
			echo $_GET['success'];
			echo "'\nRabbitMQ Message published!";
			echo "'\nEnd of RabbitMQ Testing!";
		}
	?>
</body>
</html>