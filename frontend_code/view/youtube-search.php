<?php include 'header.php'; ?>

<main role="main" class="container">
	<h1>Youtube Search</h1>
	<div class="input-group md-form form-sm form-1 pl-0">
		<div class="input-group-prepend">
			<span class="input-group-text cyan lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				aria-hidden="true"></i></span>
		</div>
		<input class="form-control my-0 py-1" type="text" placeholder="Search" aria-label="Search">
	</div>
	<script type="text/javascript">
		$('#youtube-search').addClass('active');
		$('#home').removeClass('active');
	</script>
</main><!-- /.container -->

<?php include 'footer.php'; ?>
