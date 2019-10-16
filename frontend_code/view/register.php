<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/sign-in.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet"> 
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<title>IT490 Project | Registration</title>
</head>
<body class="text-center"> 
	<form action="../model/register_script.php" method="POST" class="form-signin">
		<img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
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
		<a href="sign-in.php"><p class="h5 mb-5 font-weight-normal">Already Have An Account? Please Sign-In</p></a>
	</form>
</body>
</html>