<?php include 'header.php'; ?>

<main role="main">
	<section class="jumbotron text-center">
		<div class="container">
			<h1>YouTube Search</h1>
			<div class="input-group md-form form-sm form-1 pl-0">
				<div class="input-group-prepend">
					<span class="input-group-text cyan lighten-3" id="basic-text1"><i class="fas fa-search text-white"
						aria-hidden="true"></i></span>
				</div>
				<form id="search-form" action="../controller/index.php?action=search" method="POST">
				</form>
				<input class="form-control my-0 py-1" form="search-form" type="text" placeholder="Search" aria-label="Search" onkeydown="search(this)" name="search-terms">
			</div>
			<script type="text/javascript">
				$('#youtube-search').addClass('active');
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
					if(isset($yt_api)){					
						$yt_api->echo_html_results($data);
					}
				?>
			</div>
		</div>
	</div>
</main><!-- /.container -->

<?php include 'footer.php'; ?>
