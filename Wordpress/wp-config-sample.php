<?php
/**
 * Baskonfiguration f�r WordPress.
 *
 * Denna fil inneh�ller f�ljande konfigurationer: Inst�llningar f�r MySQL,
 * Tabellprefix, S�kerhetsnycklar, WordPress-spr�k, och ABSPATH.
 * Mer information p� {@link http://codex.wordpress.org/Editing_wp-config.php 
 * Editing wp-config.php}. MySQL-uppgifter f�r du fr�n ditt webbhotell.
 *
 * Denna fil anv�nds av wp-config.php-genereringsskript under installationen.
 * Du beh�ver inte anv�nda webbplatsen, du kan kopiera denna fil direkt till
 * "wp-config.php" och fylla i v�rdena.
 *
 * @package WordPress
 */

// ** MySQL-inst�llningar - MySQL-uppgifter f�r du fr�n ditt webbhotell ** //
/** Namnet p� databasen du vill anv�nda f�r WordPress */
define('DB_NAME', 'db');

/** MySQL-databasens anv�ndarnamn */
define('DB_USER', 'root');

/** MySQL-databasens l�senord */
define('DB_PASSWORD', '');

/** MySQL-server */
define('DB_HOST', 'localhost');

/** Teckenkodning f�r tabellerna i databasen. */
define('DB_CHARSET', 'utf8');

/** Kollationeringstyp f�r databasen. �ndra inte om du �r os�ker. */
define('DB_COLLATE', '');

/**#@+
 * Unika autentiseringsnycklar och salter.
 *
 * �ndra dessa till unika fraser!
 * Du kan generera nycklar med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Du kan n�r som helst �ndra dessa nycklar f�r att g�ra aktiva cookies obrukbara, vilket tvingar alla anv�ndare att logga in p� nytt.
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
 * Tabellprefix f�r WordPress Databasen.
 *
 * Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
 * prefix. Endast siffror, bokst�ver och understreck!
 */
$table_prefix  = 'wp_';

/**
 * WordPress-spr�k, f�rinst�llt f�r svenska.
 *
 * Du kan �ndra detta f�r att �ndra spr�k f�r WordPress.  En motsvarande .mo-fil
 * f�r det valda spr�ket m�ste finnas i wp-content/languages. Exempel, l�gg till
 * sv_SE.mo i wp-content/languages och ange WPLANG till 'sv_SE' f�r att f� sidan
 * p� svenska.
 */
define('WPLANG', '');

/** 
 * F�r utvecklare: WordPress fels�kningsl�ge. 
 * 
 * �ndra detta till true f�r att aktivera meddelanden under utveckling. 
 * Det �r rekommderat att man som till�ggsskapare och temaskapare anv�nder WP_DEBUG 
 * i sin utvecklingsmilj�. 
 */ 
define('WP_DEBUG', true);

/* Det var allt, sluta redigera h�r! Blogga p�. */

/** Absoluta s�kv�g till WordPress-katalogen. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Anger WordPress-v�rden och inkluderade filer. */
require_once(ABSPATH . 'wp-settings.php');