<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Mystery Theater | Sign-In</title>
	<link rel="stylesheet" href="../view/css/bootstrap/bootstrap-4.3.1-dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/sign-in.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet"> 
	<link rel="icon" type="image/ico" href="/images/favicon.ico">
	<script src="../view/css/bootstrap/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
</head>
<body class="text-center">
	<form method="POST" action="../model/login_script.php" class="form-signin">
		<img src="../view/images/game-controller.png" width="72" height="72" class="d-inline-block align-top mb-4" alt="Mystery Theater">
		<h1 class="h3 mb-3 font-weight-normal">Please Sign-In</h1> 
		<label class="sr-only">Username:</label>
		<input type="text" name="user-name" required placeholder="Username" class="form-control" autofocus>
		<br>
		<label class="sr-only">Password:</label>
		<input type="Password" class="form-control" placeholder="Password" name="password" required>
		<input type="hidden" name="action" value="sign-in">
		<br>
		<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-primary btn-block">
		<br>
		<a href="register.php"><p class="h5 mb-5 font-weight-normal">Don't have an account? Sign Up</p></a>
		<p class="mt-5 mb-3 text-muted">&copy; NJIT | 2019-2020</p>

	</form>
</body>
</html>