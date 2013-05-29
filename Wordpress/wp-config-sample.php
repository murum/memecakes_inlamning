<?php
/**
 * Baskonfiguration för WordPress.
 *
 * Denna fil innehåller följande konfigurationer: Inställningar för MySQL,
 * Tabellprefix, Säkerhetsnycklar, WordPress-språk, och ABSPATH.
 * Mer information på {@link http://codex.wordpress.org/Editing_wp-config.php 
 * Editing wp-config.php}. MySQL-uppgifter får du från ditt webbhotell.
 *
 * Denna fil används av wp-config.php-genereringsskript under installationen.
 * Du behöver inte använda webbplatsen, du kan kopiera denna fil direkt till
 * "wp-config.php" och fylla i värdena.
 *
 * @package WordPress
 */

// ** MySQL-inställningar - MySQL-uppgifter får du från ditt webbhotell ** //
/** Namnet på databasen du vill använda för WordPress */
define('DB_NAME', 'db');

/** MySQL-databasens användarnamn */
define('DB_USER', 'root');

/** MySQL-databasens lösenord */
define('DB_PASSWORD', '');

/** MySQL-server */
define('DB_HOST', 'localhost');

/** Teckenkodning för tabellerna i databasen. */
define('DB_CHARSET', 'utf8');

/** Kollationeringstyp för databasen. Ändra inte om du är osäker. */
define('DB_COLLATE', '');

/**#@+
 * Unika autentiseringsnycklar och salter.
 *
 * Ändra dessa till unika fraser!
 * Du kan generera nycklar med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Du kan när som helst ändra dessa nycklar för att göra aktiva cookies obrukbara, vilket tvingar alla användare att logga in på nytt.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '&Gv}+)O4&cU#oq$sPJ+3]Td ;mw%GRo!O$$#F}u9IC&;kFQ;,UN>{dz1.W,-RbTk');
define('SECURE_AUTH_KEY',  '~ag&)T=8U<5hWly|t;#a:7e&`>Dl!m Mf 2_tZO 4a`=YB,:qG`&p>V65x-/u7dG');
define('LOGGED_IN_KEY',    'spnG&[Qhw9 x)BnP%O5GP@=%+^)?7_,-WbU[)3D=j.c#;4{rGLGocc2Nn^*kw{]z');
define('NONCE_KEY',        'sW/(+w%y8_c%wDO1s+fFiRi%cr~.r5eNI!ZUO<]evH8QeV),Zk:X73k8h0ArVj~S');
define('AUTH_SALT',        'R+_|IRdIv+gy8*J#K(vA$o`(}EJ-3/D[4~7 vU$I49+P2vtrED5S .@[in~=Hd>F');
define('SECURE_AUTH_SALT', '4}{Ejk%XPH{;EWk,/sz,&[vu7xT;yGb4{yKjPmu);CrtY*|/RJc&TZ>U`^&;p<||');
define('LOGGED_IN_SALT',   'Pdg.Y%bC.W 6 QyY_sa])~^F%XP,XcCJ8Eycq*qToV.m[sR[]IU.0NQc>kms4ZTX');
define('NONCE_SALT',       'G_hb|#Ur|j0Lzzr{+xF-TTzq9wSUN1IIyJlF1(C#ge#bi7yx<|#|(K6E4(kr.e@s');

/**#@-*/

/**
 * Tabellprefix för WordPress Databasen.
 *
 * Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
 * prefix. Endast siffror, bokstäver och understreck!
 */
$table_prefix  = 'wp_';

/**
 * WordPress-språk, förinställt för svenska.
 *
 * Du kan ändra detta för att ändra språk för WordPress.  En motsvarande .mo-fil
 * för det valda språket måste finnas i wp-content/languages. Exempel, lägg till
 * sv_SE.mo i wp-content/languages och ange WPLANG till 'sv_SE' för att få sidan
 * på svenska.
 */
define('WPLANG', '');

/** 
 * För utvecklare: WordPress felsökningsläge. 
 * 
 * Ändra detta till true för att aktivera meddelanden under utveckling. 
 * Det är rekommderat att man som tilläggsskapare och temaskapare använder WP_DEBUG 
 * i sin utvecklingsmiljö. 
 */ 
define('WP_DEBUG', true);

/* Det var allt, sluta redigera här! Blogga på. */

/** Absoluta sökväg till WordPress-katalogen. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Anger WordPress-värden och inkluderade filer. */
require_once(ABSPATH . 'wp-settings.php');