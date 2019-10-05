<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>IT490 Project | Sign-In</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/sign-in.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet"> 
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body class="text-center">
	<form method="POST" action="../model/login_script.php" class="form-signin">
		<img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
		<h1 class="h3 mb-3 font-weight-normal">Please Sign-In</h1> 
		<label class="sr-only">Username:</label>
		<input type="text" name="user-name" required placeholder="Username" class="form-control" autofocus>
		<label class="sr-only">Password:</label>
		<input type="Password" class="form-control" placeholder="Password" name="password" required>
		<input type="hidden" name="action" value="sign-in">
		<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-primary btn-block">
		<br>
		<a href="register.php"><p class="h5 mb-5 font-weight-normal">Don't have an account? Sign Up</p></a>
		<p class="mt-5 mb-3 text-muted">&copy; NJIT | 2019-2020</p>

	</form>
</body>
</html>