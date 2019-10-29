<!-- Header -->
<?php include 'header.php'; ?>

<main role="main" >
	<div class="shadow rounded position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-dark">
      <div class="col-md-5 p-lg-5 mx-auto my-5">
        <h1 class="display-5 font-weight-normal text-warning">Mystery Theater</h1>
        <p class="lead font-weight-normal text-white">View your least-played games, see what makes them special.</p>
      </div>
    </div>
    <div class="row mx-2 my-5">
 		<div class="col-md-5">
 			<h2 class="text-center pb-3" style="color: red;">About</h2>
 			<p>Sometimes, we have so many games in our backlog that some never get played or are forgotten. Mystery Theater's goal is to show you the games that you are missing out on your Steam library.</p>
 			<p>It combines both Steam, YouTube, and Twitch Web services to showcase the games you haven't been playing a lot through stream playthroughs and video clips.</p>
 			<p>The goal is to reignite your interest in these games you've purchased before by allowing the streamers to show you its mechanics, story, gameplay, and art direction. </p>
 		</div>
 		<div class="col-md-7">
 			<img src="http://localhost/it490-proj/frontend_code/view/images/game-controller.png" class="rounded mx-auto d-block" height="300" width="300" alt="controller">
 		</div>
 	</div>
 	<hr>
 	<div class="row mx-2 my-3">
 		<div class="col-md-5">
 			<h2 class="text-center pb-3" style="color: red;">Steam Integration</h2>
 			<p><b>By connecting with your Steam ID, you can:</b></p>
 			<ul class="points" style="list-style-type: none;">
 				<li>&hyphen; View your least played games</li>
 				<li>&hyphen; Compare Match History &amp; Leaderboard Achievements with your friends</li>
 				<li>&hyphen; Examine your friend's least played games &amp; when they're on sale</li>
 				<li>&hyphen; Read brief information about each game (from Steam Store)</li>
 				<li>&hyphen; Select which type of video-search (YouTube or Twitch) you'd like to view the game's content for</li>
 			</ul>
 		</div>
 		<div class="col-md-7 d-flex flex-wrap align-items-center">
 			<img src="http://localhost/it490-proj/frontend_code/view/images/steam.png" class="rounded mx-auto d-block mt-5" height="180" width="500" alt="controller">
 		</div>
 	</div>
 	<hr>
 	<div class="row mx-2 my-3">
 		<div class="col-md-5">
 			<h2 class="text-center pb-3" style="color: red;">YouTube/Twitch Integration</h2>
 			<ul class="points" style="list-style-type: none;">
 				<li >&hyphen; View clips/streams of any game of your choice</li>
 				<li >&hyphen; Watch YouTube clips based on the game's tag(s)</li>
 				<li >&hyphen; Search for any game title</li>
 			</ul>
 		</div>
 		<div class="col-md-7 d-flex flex-wrap align-items-center">
 			<img src="http://localhost/it490-proj/frontend_code/view/images/twitch-placeholder.png" class="rounded mx-auto d-block" height="150" width="150" alt="controller">
 		</div>
    </div>
    <hr>
    <div class="row mx-2 my-3 pb-5">
    	<div class="col-sm-12">
    		<h2 class="text-center pb-3" style="color: red;">Developers</h2>
    	</div>
    	<div class="col-md-3">
    		<h4 class="text-center text-primary ">DMZ</h4>
    	</div>
    	<div class="col-md-3">
    		<h4 class="text-center text-primary">Messaging Broker</h4>
    	</div>
    	<div class="col-md-3">
    		<h4 class="text-center text-primary">Database/Backend</h4>
    	</div>
    	<div class="col-md-3">
    		<h4 class="text-center text-primary">Frontend</h4>
    	</div>
    	<div class="col-md-3">
    		<p class="text-center"><b>Anthony Duran</b></p>
    	</div>
    	<div class="col-md-3">
    		<p class="text-center"><b>Faddy Hadad</b></p>
    	</div>
    	<div class="col-md-3">
    		<p class="text-center"><b>Alex Kronish</b></p>
    	</div>
    	<div class="col-md-3">
    		<p class="text-center"><b>Kevin Solorzano</b></p>
    	</div>

    </div>

</main><!-- /.container -->
<script type="text/javascript">
	$('#home a').css('color', '#FFD700');
	$('.points li').css('padding', '10px');
</script>

<!-- Footer -->
<?php include 'footer.php'; ?>