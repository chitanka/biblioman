<!DOCTYPE html>
<html lang="en">
<head>
	<base href="http://detstvoto.net">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Фантопия">
	<meta name="author" content="Владимир Кромбърг">
	<title>Фантопия</title>

	<!-- CSS -->
	<!-- Bootstrap and demo CSS -->
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/navigation-puk.css" rel="stylesheet">
	<link href="css/panels.css" rel="stylesheet">
	<link href="css/tipography.css" rel="stylesheet">
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/site.css" rel="stylesheet">

	<!-- Стилове за икони -->
	<link rel="stylesheet" type="text/css" href="ficons.css">

</head>
<body>

<div class="section gray">
	<div class="contain-wrapp">
		<div class="container">
			<div class="row">

				<div class="col-md-12 col-sm-12">
					<article class="article">

						<section id="books">
							<h2 class="page-header">Икони за книги (За НД, МБ, БМ, ПУК!, SFBG, Фантопия...)</h2>

							<?php $sizes = [
								'',
								'ficon-hc-lg',
								'ficon-hc-2x',
								'ficon-hc-3x',
								'ficon-hc-4x',
								'ficon-hc-5x',
							] ?>
							<?php foreach ($sizes as $size) { ?>
								<h2><?= $size ?: 'ficon' ?></h2>
							<ul class="list-inline">
								<?php for ($i = 94; $i <= 116; $i++) { ?>
									<?php $icon = str_pad($i, 3, '0', STR_PAD_LEFT) ?>
									<li><span class="ficon ficon-<?php echo $icon ?> <?php echo $size ?>" title="<?php echo $icon ?>"></span> <?php echo $icon ?></li>
								<?php } ?>
							</ul>
							<?php } ?>
						</section>
					</article>



					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Start Footer -->
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-xs-6">
					© Фантопия 2015-2017</div>
				<div class="col-xs-6 text-right">
					<a href="#" >връзка 1</a> \ <a href="#" >връзка 1</a>
				</div>
			</div>
		</div>
	</footer>
</div>
	<!-- Bootstrap core JavaScript-->
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/main.js"></script>
	<script>
		$(function() {
			window.prettyPrint && prettyPrint()
			$(document).on('click', '.ft-navbar .dropdown-menu', function(e) {
				e.stopPropagation()
			})
		})
	</script>

</body>
</html>
