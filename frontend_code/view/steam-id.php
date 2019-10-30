<?php include 'header.php'; ?>

<main role="main">

	<!-- 1st Row: Steam Profile Info -->
	<div class="row mx-4">
		<!-- Col 12: Steam Profile Info & Owned Games List --> 
		<div class="col-md-12 text-center">
			<h3 style="color: red;">Welcome, 
			<?php 
				if(isset($api))
					echo $api->get_user_info_array()[0]['personaname'];
				else
					echo "Gary";
			?>
			</h3><hr>
			<img src="<?php if(isset($api)) echo $api->get_user_info_array()[0]['avatar']; ?>" width="150" height="150" class="img-fluid rounded mx-auto d-block">
			<h5>Persona Name: <?php if(isset($api)) echo $api->get_user_info_array()[0]['personaname']; ?></h5>
			<h5>Steam URL: <a target="_blank" href="<?php if(isset($api)) echo $api->get_user_info_array()[0]['profileurl']; ?>">Steam-Community Link</a></h5>
		</div>
	</div>

	<!-- 2nd Row: Games List/Friends List -->
	<div class="row mx-4 my-5">
		<!-- Col-6: Games List Section (Left Half of page) -->
		<div class="col-md-6" >
			<div class="row my-5">
				<div class="col-md-12">
					<h3 class="text-center" style="color: red;">Least Played Games</h3><hr>
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
			<div class="row my-5">
				<div class="col-md-12">
					<h3 class="text-center" style="color: red;">Friends</h3><hr>
					<div class="friend-list shadow rounded bg-white">
						<?php 
							if(isset($api))
								$api->echo_html_friend_list();
							else
								echo "No friends available.";
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- 3rd Row: Match History -->
	<div class="row mx-4 py-4">
		<!-- Col 12: Heading -->
		<div class="col-md-12">
			<h3 style="color: red;" class="text-center">Match History</h3>
			<hr>
		</div>

		<!-- Col 6: Select match result with friend -->
		<div class="col-md-8">
			
				<select id="friend">
					<?php
					if(isset($api))
						foreach($api->get_friends_array() as $friend)
							echo "<option>".$friend['personaname']."</option>";
					?>
				</select>
				has
				<select id="outcome">
					<option>won</option>
					<option>lost</option>
				</select>
				against you
				<select id="num-matches">
					<?php 
					for($i=1; $i <= 10; $i++)
						echo "<option>".$i."</option>";
					?>
				</select>
				time(s) in matches.
				<button class="my-2 btn btn-md btn-primary" id="update">Update</button>
				<button class="my-2 btn btn-md btn-warning" id="view">View History</button>
		</div>

		<!-- Col 6: View Match History Ratio -->
		<div class="col-md-4">
			<table style="width:100%" class="d-none">
				<tr>
					<th style="color: red;">Friend</th>
					<th style="color: red;">Win/Loss Ratio</th>
				</tr>
				<tr>
					<td id="opponent">Jake</td>
					<td id="ratio">1:2</td>
				</tr>
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
					error: function(response){
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

			$('#update').click(function(event){
				var friend = $('#friend :selected').val();
				var outcome = $('#outcome :selected').val();
				var num = $('#num-matches :selected').val();
				var user = "<?php echo $api->get_user_info_array()[0]['personaname']; ?>"
				console.log("Log: " + friend +" " + outcome +" " + num + " " + user);
				var data = "action=match-history&user=" + user + "&outcome=" + outcome + "&friend="+ friend + "&num-matches=" + num;
				$.ajax({
					url: 'http://localhost/it490-proj/frontend_code/controller/index.php',
					type: 'get',
					data: data,
					success: function(response){
						//$('#ratio').text(response);
					},
					error: function(response){
						//$('#ratio').text('Request couldn\'t go through');
					}
				});
				//$('.d-none').attr('class', '');
				$('#opponent').text(friend);

			});

			$('#view').click(function(event){
				var friend = $('#friend :selected').val();
				var user = "<?php echo $api->get_user_info_array()[0]['personaname']; ?>"
				console.log("Log: " + friend +" " + user);
				var data = "action=view-history&user=" + user + "&friend="+ friend;
				$.ajax({
					url: 'http://localhost/it490-proj/frontend_code/controller/index.php',
					type: 'get',
					data: data,
					success: function(response){
						$('#ratio').text(response);
					},
					error: function(response){
						$('#ratio').text('Request couldn\'t go through');
					}
				});
				$('.d-none').attr('class', '');
				$('#opponent').text(friend);

			});
		});
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