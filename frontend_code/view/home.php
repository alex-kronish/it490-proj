<!-- Header -->
<?php include 'header.php'; ?>

<main role="main" class="container">
	<div class="starter-template">
		<h1>Welcome Fellow User!</h1>
		<p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
	</div>

</main><!-- /.container -->

<?php 
if(isset($_GET['success'])){
	echo $_GET['success'];
	echo "'\nRabbitMQ Message published!";
	echo "'\nEnd of RabbitMQ Testing!";
}
?>
<!-- Footer -->
<?php include 'footer.php'; ?>