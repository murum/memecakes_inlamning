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
                <li><a href="#">footer.php</a></li>
            </ul>

            <h2>Footer.php</h2>
            <p>Här är footern som används vid alla templates, footer.php är det som skrivs ut när man använder WordPress funktionen get_footer() </p>
            <p>Här länkas alla javascript in samt avslutar de taggarna som har blivit öppnade sedan tidigare.</p>
        </div>
        <div class="sidebar"><?php include('../includes/sidebar.php'); ?></div>
    </div>
</body>
</html>