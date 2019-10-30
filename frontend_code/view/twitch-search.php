<?php include 'header.php'; ?>

<main role="main">
	<section class="jumbotron text-center bg-dark mx-5">
		<div class="container">
			<h1 class="text-warning">Twitch Stream Search</h1>
			<div class="input-group md-form form-sm form-1 pl-0">
				<div class="input-group-prepend">
					<span class="input-group-text cyan lighten-3" id="basic-text1"><i class="fas fa-search text-white"
						aria-hidden="true"></i></span>
				</div>
				<form id="search-form" action="../controller/index.php?action=stream-search" method="GET">
				</form>
				<input class="form-control my-0 py-1" form="search-form" type="text" placeholder="Search Stream by Game Title" aria-label="Search" onkeydown="search(this)" name="search-terms">
				<input type="hidden" name="action" value="stream-search" form="search-form">
			</div>
			<script type="text/javascript">
				$('#twitch-search').addClass('active');
				$('#twitch-search a').css('color', '#FFD700');
				$('#home').removeClass('active');

				function search() {
					if(event.key === 'Enter') {
				    	document.getElementById('search-form').submit();
				    }
				}
			</script>
		</div>
	</section>

	<div class="album py-4 bg-light">
		<div style="margin-left: 5%; margin-right: 5%; ">
			<div class="row">
				<!-- Echo html row from search results here -->
				<?php
					if(isset($api)){					
						$api->echo_html_results();
					}
				?>
			</div>
		</div>
	</div>
</main><!-- /.container -->

<?php include 'footer.php'; ?>
