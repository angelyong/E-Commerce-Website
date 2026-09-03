<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title> ABOUT | E-COMMERCE WEBSITE </title>
		<!-- favicon -->
		<link rel="stylesheet" href="<?php echo asset('assets/css/about.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
	</head>

	<body>
		<!-- HEADER -->
		<?php include 'includes/header.php'; ?>
		
		<header id="header1">
			<h1> About Us </h1>
		</header>
		
		<!-- FEATURED TEASER -->
		<div id="contents">
			<h2> <b>SHOP</b>LANE </h2>
			<p> This is an E-Commerce website that aims to facilitate clothes and accessories shopping. </p>
			<p> The ultimate goal is to provide a <q>fast <b>lane</b></q> for customer to shop.</p>
			<br><br>
			<h4> Enjoy yourself and </h4>
			<p> Even if you didnt buy anything </p>
			<h4> Thanks for using us </h4>
			
		</div>

		<?php include 'includes/footer.php'; ?>

	</body>

</html>
