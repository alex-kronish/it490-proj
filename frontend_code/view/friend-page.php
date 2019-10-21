<?php include 'header.php'; ?>

<main role="main">
	<div class="row mx-4 mb-5">
		<div class="col-md-12">
			<h3 style="color: red;">Welcome, 
			<?php 
				if(isset($_SESSION['user']))
					echo $_SESSION['user']->getSteamID();
				else
					echo "Gary";
			?>
			</h3><hr>
			<img src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/5a/5a1e3659496fb7f93d455bf842ab9db503167433.jpg" width="150" height="150" class="rounded mx-auto d-block">
			<h5 class="text-center">Persona Name:</h5>
			<h5 class="text-center">Based: NJ, USA</h5>
			<h5 class="text-center">Profile URL:</h5>
		</div>
	</div>
	<div class="row mx-4">
		<div class="col-md-12 text-right">
			<p>Cyan: Games mutually shared </p>
		</div>
	</div>
	<div class="row mx-4">
		<div class="col-md-12">
			<h3 style="color: red;">Least Played Games</h3><hr>
			<div class="games-list shadow rounded bg-white">
				<?php 
					if(isset($api))
						$api->echo_html_friend_owned_games($data);
					else
						echo "No games available.";
				?>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#steam').addClass('active');
			$('#steam a').css('color', '#FFD700');
			$('#home').removeClass('active');
			$('a').click(function(event){
				var id = "#".concat($(this).attr("id"));
				var title = $(id).text();
				var ques = "What type of videos do you wish to view for ".concat(title).concat("?");
				$('.modal-body').text(ques);
			});
		});
	</script>
</main><!-- /.container -->

<?php include 'footer.php'; ?>