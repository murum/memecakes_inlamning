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
                <li><a href="#">author.php</a></li>
            </ul>

            <h2>Author.php</h2>
            <p>Det här är en av de större templatesen i projektet, den här templaten innehåller allting som har med en användares profil att göra, allt från listning av memecakes till editerandet av sin egna profil.</p>
            <p>För att veta vilken vy som ska genereras ut mot användaren så används olika post variabler och kontrollerar vilken post variabel som är satt och vilken som inte är satt. Hur dessa används finns dokumenterat mer i filen.</p>
            <h3>Ansvar för</h3>
            <ul class="responsible">
                <li>Editera Profil</li>
                <li>Visa Profil</li>
                <li>Visa Användares uppladdade memecakes</li>
                <li>Om det är din egna profil så visas en upload knapp om du inte har laddat upp några memecakes</li>
                <li>Om det inte är din egna profil och inga memecakes finns uppladdade så visas det att det inte finns några memecakes</li>
            </ul>
        </div>
        <div class="sidebar"><?php include('../includes/sidebar.php'); ?></div>
    </div>
</body>
</html>