<?php include 'header.php'; ?>

<main role="main">
	<div class="row mx-4">
		<div class="col-md-6 text-center" >
			<h3 style="color: red;">Enter Your Steam</h3>
			<form action="../controller/index.php?action=find-steam-id" method="POST" class="mt-5">
				<input type="text" name="vanity-url" placeholder="Enter your vanity url" class="form-control">
				<br>
				<input type="submit" name="submit" value="Register Steam ID" class="btn btn-lg btn-primary btn-block">
			</form>
			
		</div>
		<div class="col-md-6 text-center">
			<h3 style="color: red;">How To Get Your Steam ID</h3>
			<ol class="text-left">
				<li>Launch any Valve multiplayer game.</li>
				<li>Click Options.</li>
				<li>Select the Keyboard tab.</li>
				<li>Click the Advanced button.</li>
				<li>Check the box labeled Enable Developer Console.</li>
				<li>Click Apply and then click OK.</li>
				<li>Join or create a server.</li>
				<li>From the menu screen, press the tilde ~ key - typically in the upper left hand corner of the keyboard to load the Console.</li>
				<li>Type status into the console text field and press the Enter key.</li>
				<li>Your SteamID will be displayed next to your nickname in the console window.</li>
			</ol>
		</div>
		
	</div>
	<script type="text/javascript">
		$('#steam').addClass('active');
		$('#home').removeClass('active');
	</script>
</main><!-- /.container -->

<?php include 'footer.php'; ?>