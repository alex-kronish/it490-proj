<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Project site for IT490 titled Mystery Steam Theater">
	<meta name="author" content="Kevin Solorzano, Alex Kronish, Faddy Hadad, Anthony Duran">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../view/css/bootstrap/bootstrap-4.3.1-dist/css/bootstrap.min.css">
	<script src="https://kit.fontawesome.com/10c461c81c.js" crossorigin="anonymous"></script>
	<script src="../view/css/jquery/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="../view/css/bootstrap/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
	<link rel="icon" href="/images/favicon.ico">
	<link type="text/css" href="../view/css/index.css" rel="stylesheet">
	<title>Mystery Theater</title>
</head>
<body>

	<nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: #6441A5;">
		<a class="navbar-brand" href="../controller/index.php?action=view-home">
    		<img src="../view/images/game-controller.png" width="30" height="30" class="d-inline-block align-top"> Mystery Theater
  		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active" id="home">
					<a class="nav-link" href="../controller/index.php?action=view-home">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item" id="steam">
					<a class="nav-link" href="../controller/index.php?action=view-steam-id">Steam</a>
				</li>
				<li class="nav-item" id="youtube-search">
					<a class="nav-link" href="../controller/index.php?action=view-youtube-search">Youtube Search</a>
				</li>
				<li class="nav-item" id="twitch-search">
					<a class="nav-link" href="../controller/index.php?action=view-twitch-search">Twitch Search</a>
				</li>
				<li class="nav-item" id="sign-out">
					<a class="nav-link pull-right" href="../controller/index.php?action=sign-out">Sign Out</a>
				</li>
			</ul>
		</div>
	</nav>

    <!-- Header Section ends here -->