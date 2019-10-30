<?php include 'header.php'; ?>

<main role="main">
	<!-- Row 1: Steam Profile Info --> 
	<div class="row mx-4">
		<div class="col-md-12 text-center">
			<h3 style="color: red;">Friend: 
			<?php 
				if(isset($api))
					echo $api->get_user_info_array()[0]['personaname'];
				else
					echo "User";
			?>
			</h3><hr>
			<img src="<?php if(isset($api)) echo $api->get_user_info_array()[0]['avatar']; ?>" width="150" height="150" class="img-fluid rounded mx-auto d-block">
			<h5>Persona Name: <?php if(isset($api)) echo $api->get_user_info_array()[0]['personaname']; ?></h5>
			<h5>Steam URL: <a target="_blank" href="<?php if(isset($api)) echo $api->get_user_info_array()[0]['profileurl']; ?>">Steam-Community Link</a></h5>
		</div>
	</div>

	<!-- Row 2: Friend's Least Played Games List -->
	<div class="row mx-4">
		<div class="col-md-12 my-5" >
			<span>
				<h3 class="text-center" style="color: red;">Least Played Games</h3>
				<p class="text-right"><b style="color: #ffd27f;">Highlight:</b> Games mutually shared</p>
				<hr>
			</span>
			<div class="games-list shadow rounded bg-white pt-2">
				<?php 
					if(isset($api) && isset($user))
						$api->echo_html_friend_owned_games($user->get_least_played_games());
					else
						echo "No games available.";
				?>
			</div>
		</div>
	</div>

	<!-- Row 3: Leaderboard Achievement List -->
	<div class="row mx-4">
		<!-- Col 12: Heading -->
		<div class="col-md-12">
			<h3 style="color: red;" class="text-center">Achievement Leaderboard</h3>
			<hr>
		</div>

		<div class="col-md-4 mb-5">
			<select id="games" onchange="leaderboard()">
				<?php 
					foreach($api->get_mutal_games_array() as $key)
						echo "<option id=\"".$key['app-id']."\">".$key['name']."</option>";
				?>
			</select>
		</div>
		<div class="col-md-8">
			<table style="width:100%">
				<thead>
					<tr>
						<th>Achievement Name</th>
						<th>You</th>
						<th><?php echo $api->get_user_info_array()[0]['personaname']; ?></th>
					</tr>
				</thead>
				<tbody id="tbodyid">	
				</tbody>
			</table>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#steam').addClass('active');
			$('#steam a').css('color', '#FFD700');
			$('#home').removeClass('active');
			$('a.game').click(function(event){
				var id = "#".concat($(this).attr("id"));
				var title = $(id).text();
				var ques = "What type of videos do you wish to view for ".concat(title).concat("?");
				var data = "action=request-game-info&app-id=" + id.substring(5);
				$.ajax({
					url: 'http://localhost/it490-proj/frontend_code/controller/index.php',
					type: 'get',
					data: data,
					success: function(response){
						$('#game-info').text('').append(response);
					},
					error: function($response){
						$('#game-info').text('').append('Request couldn\'t go through');
					}
				});
				$('#prompt-body').text(ques);
			});
			$('#youtube').click(function(event){
				var title = $('#prompt-body').text().substring(44).split('?');
				$(this).attr('href', "../controller/index.php?action=search&search-terms=".concat(title[0]));
			});

			$('#twitch').click(function(event){
				var title = $('#prompt-body').text().substring(44).split('?');
				$(this).attr('href', "../controller/index.php?action=stream-search&search-terms=".concat(title[0]));
			});
		});

		function leaderboard(){
			var game = $('#games').children(":selected").attr("id");
			var friend = "<?php if(isset($api)) echo $api->get_user_info_array()[0]['steamid']; ?>";
			var data = "action=leaderboard&game-title=" + game + "&friend-steam-id=" + friend;
			$.ajax({
				url: 'http://localhost/it490-proj/frontend_code/controller/index.php',
				type: 'get',
				data: data,
				success: function(response){
					console.log('AJAX Response: ' + response);
					var q = jQuery.parseJSON(response);
					$('#tbodyid').empty();
					for(var i = 0; i < q.length; i++){
						console.log(q[i]);
						$('#tbodyid').append(q[i]);
					}
				
				},
				error: function(response){
					console.log("Controller couldn't process request.");
				}
			});
		}
	</script>
</main><!-- /.container -->

<!-- Modal -->
<div class="modal fade" id="gamemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog mw-100 w-75" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Game Info </h5>
        <div id="discount-img"></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="game-info"></div>
      	<p id="prompt-body" style="color: red"><b>What type of videos do you wish to view for this game?</b></p>
      </div>
      <div class="modal-footer">
        <a href="../controller/index.php?action=view-youtube-search" id="youtube"><button type="button" class="btn btn-primary" style="background-color: red; color: white;">YouTube</button></a>
        <a href="../controller/index.php?action=view-twitch-search" id="twitch"><button type="button" class="btn btn-primary" style="background-color: #6441A5; color: white;">Twitch</button></a>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>