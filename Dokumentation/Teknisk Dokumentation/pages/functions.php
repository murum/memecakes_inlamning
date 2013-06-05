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
                <li><a href="#">functions.php</a></li>
            </ul>

            <h2>Functions.php</h2>
            <p>Här finns det väldigt mycket och koden är väl dokumenterad i filen.</p>
            <h3>Filer som den inkluderar</h3>
            <dl class="included_files">
                <dt>memecake_auth.php</dt>
                <dd>Memecake Auth tar hand om alla ajax anrop som kommer ifrån authentiserings funktionaliteten d.v.s. Registrering, Logga in, Glömt lösenord.</dd>
                <dt>memecake_posttype.php</dt>
                <dd>Memecake Posttype skapar en custom post type för en memecake</dd>
                <dt>memecake_taxonomies.php</dt>
                <dd>Memecake Taxonomies lägger till de taxonomies som behövs för en memecake. I det här fallet lägger den till Meme. Men inte datan som ligger i meme taxonomien utan den måste matas in manuellt och den är i behov av plug-innet <a href="http://wordpress.org/plugins/s8-simple-taxonomy-images/">S8 Simple taxonomy images</a></dd>
                <dt>memecake_social_contact.php</dt>
                <dd>Memecake Social Contact lägger till custom user meta data för att skapa data för en användare som man kan lagra en användares social media användarnamn.</dd>
            </dl>
        </div>
        <div class="sidebar"><?php include('../includes/sidebar.php'); ?></div>
    </div>
</body>
</html>