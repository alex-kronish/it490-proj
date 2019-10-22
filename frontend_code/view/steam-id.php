<?php include 'header.php'; ?>

<main role="main">
	<div class="row mx-4">
		<!-- Col 6: Steam Profile Info & Owned Games List (Left half of page) --> 
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
			<h5>Based: NJ, USA</h5>
			<h5>Steam URL: <a target="_blank" href="<?php if(isset($api)) echo $api->get_user_info_array()[0]['profileurl']; ?>">Steam-Community Link</a></h5>
		</div>
	</div>

	<div class="row mx-4 my-5">
		<div class="col-md-6" >
			<div class="row my-5">
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
			<div class="row my-5">
				<div class="col-md-12">
					<h3 style="color: red;">Friends</h3><hr>
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