<?php include 'header.php'; ?>

<main role="main">
	<div class="row mx-4">
		<!-- Col 6: Steam Profile Info & Owned Games List (Left half of page) --> 
		<div class="col-md-6" >
			<div class="row">
				<div class="col-md-12">
					<h3 style="color: red;">Welcome, 
					<?php 
						if(isset($_SESSION['user']))
							echo $_SESSION['user']->getSteamID();
						else
							echo "Gary";
					?>
					</h3><hr>
					<img src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/5a/5a1e3659496fb7f93d455bf842ab9db503167433.jpg" width="100" height="100" class="rounded mx-auto d-block">
					<h5>Persona Name:</h5>
					<h5>Based: NJ, USA</h5>
				</div>
				<div class="col-md-12">
					<h3 style="color: red;">Least Played Games</h3><hr>
					<div class="games-list shadow rounded bg-white">
						<?php 
							if(isset($api))
								$api->echo_html_owned_games();
							else
								echo "No games available.";
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Col-6: Friends List Section (Right Half of page) -->
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12">
					<h3 style="color: red;">Friends</h3><hr>
					<div class="friend-list shadow rounded bg-white">
						<?php 
							if(isset($api))
								$api->echo_html_owned_games();
							else
								echo "No games available.";
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#steam').addClass('active');
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

<!-- Modal -->
<div class="modal fade" id="gamemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Options</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        What type of videos do you wish to view for this game?
      </div>
      <div class="modal-footer">
        <a href="../controller/index.php?action=view-youtube-search"><button type="button" class="btn btn-primary" style="background-color: red; color: white;">YouTube</button></a>
        <a href="../controller/index.php?action=view-twitch"><button type="button" class="btn btn-primary" style="background-color: #6441A5; color: white;">Twitch</button></a>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>