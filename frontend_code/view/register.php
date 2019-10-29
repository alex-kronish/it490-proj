<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="../view/css/bootstrap/bootstrap-4.3.1-dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/sign-in.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
	<link rel="icon" href="/images/favicon.ico">
	<script src="../view/css/bootstrap/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
	<title>Mystery Theater | Registration</title>
</head>
<body class="text-center"> 
	<form action="../model/register_script.php" method="POST" class="form-signin">
		<img src="../view/images/game-controller.png" width="72" height="72" class="d-inline-block align-top mb-4" alt="Mystery Theater">
		<h1 class="h3 mb-3 font-weight-normal">Please Fill-In All Fields</h1>
		<label class="sr-only">Username:</label>
		<input type="text" name="user-name" placeholder="Username" class="form-control" autofocus required>
		<br>
		<label class="sr-only">Password:</label>
		<input type="password" class="form-control" placeholder="Password" name="password" required>
		<br>
		<label class="sr-only">Email Address:</label>
		<input type="email" class="form-control" name="email" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" required>
		<input type="hidden" name="action" value="register">
		<br>
		<label class="sr-only">Steam ID</label>
		<input type="text" class="form-control" name="steam-id" placeholder="Steam-ID" required>
		<input type="hidden" name="action" value="register">
		<br>
		<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-primary btn-block">
		<br>
		<a href="tutorial.php" class="h5 pb-5">Can't find your Steam ID?</a>
		<a href="sign-in.php"><p class="h5 mb-5 font-weight-normal">Already Have An Account? Please Sign-In</p></a>
	</form>
</body>
</html>