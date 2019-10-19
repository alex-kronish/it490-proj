<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Project site for IT490 titled Mystery Steam Theater">
	<meta name="author" content="Kevin Solorzano, Alex Kronish, Faddy Hadad, Anthony Duran">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<script src="https://kit.fontawesome.com/10c461c81c.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link rel="icon" href="images/favicon.ico">
	<link type="text/css" href="../view/css/index.css" rel="stylesheet">
	<title>Mystery Steam Theater</title>
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
				<li class="nav-item">
					<a class="nav-link" href="#">Link</a>
				</li>
				<li class="nav-item" id="youtube-search">
					<a class="nav-link" href="../controller/index.php?action=view-youtube-search">Youtube Search</a>
				</li>
				<li class="nav-item dropdown" id="steam">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Steam</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
						<a class="dropdown-item" href="../controller/index.php?action=view-steam-id">View Steam Profile</a>
						<a class="dropdown-item" href="#">Friends List</a>
						<a class="dropdown-item" href="#">Something else here</a>
					</div>
				</li>
			</ul>
		</div>
	</nav>

    <!-- Header Section ends here -->