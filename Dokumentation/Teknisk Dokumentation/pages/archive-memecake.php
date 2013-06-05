<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="UTF-8">
	<title>Dokumentation för memecak.es</title>
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.10.1/build/cssreset/cssreset-min.css">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
	<div class="container">
		<h1>Memecakes dokumentation</h1>
		<div class="menu">
			<ul>
				<li><a href="../">Start</a></li>
				<li><a class="active" href="../documentation.php">Dokumentation</a></li>
				<li><a href="../about.php">Om Memecakes Doc</a></li>
			</ul>
		</div>
		<div class="content">
			<ul class="breadcumb">
				<li><a href="../documentation.php">Dokumentation</a></li>
				<li><a href="./archive.php">archive.php</a></li>
				<li><a href="#">archive-memecake.php</a></li>
			</ul>

			<h2>archive-memecake.php</h2>
			<p>Den här templaten används på sidan all memes. Det är ett något missvisande namn på arkivet men det är kopplat till custom post typen memecakes och det är därför också filen heter som den gör eftersom den har sitt ursprung från WordPress template hierarchy</p>
			<p>Templaten listar alla memes som finns på memecak.es och tar ut maximalt fyra stycken memecakes i de olikea memesen. Man kan sortera efter bokstavsordning eller antalet memecakes som finns i kategorin.</p>
		</div>
		<div class="sidebar"><?php include('../includes/sidebar.php'); ?></div>
	</div>
</body>
</html>