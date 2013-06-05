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
                <li><a href="#">memecakes-upload.php</a></li>
            </ul>

            <h2>memecakes-upload.php</h2>
            <p>En template som används för att ladda upp en memecake. Används på sidan "Upload"</p>
            <p>Filen är väldokumenterad och den har olika states beroende på vilken ajax request som sker. I början av filen så finns det flera if-satser som håller koll på i vilket läge upload sidan är i.</p>
        </div>
        <div class="sidebar"><?php include('../includes/sidebar.php'); ?></div>
    </div>
</body>
</html>