<?php
/** 
 * A WordPress fő konfigurációs állománya
 *
 * Ebben a fájlban a következő beállításokat lehet megtenni: MySQL beállítások
 * tábla előtagok, titkos kulcsok, a WordPress nyelve, és ABSPATH.
 * További információ a fájl lehetséges opcióiról angolul itt található:
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php} 
 *  A MySQL beállításokat a szolgáltatónktól kell kérni.
 *
 * Ebből a fájlból készül el a telepítési folyamat közben a wp-config.php
 * állomány. Nem kötelező a webes telepítés használata, elegendő átnevezni 
 * "wp-config.php" névre, és kitölteni az értékeket.
 *
 * @package WordPress
 */

// ** MySQL beállítások - Ezeket a szolgálatótól lehet beszerezni ** //
/** Adatbázis neve */
define('DB_NAME', 'menteh01_ment_wp');

/** MySQL felhasználónév */
define('DB_USER', 'menteh01_twpuser');


/** MySQL jelszó. */
define('DB_PASSWORD', 'W_3b5S2YxbxQKc2Mx');

/** MySQL  kiszolgáló neve */
define('DB_HOST', 'localhost');

/** Az adatbázis karakter kódolása */
define('DB_CHARSET', 'utf8');

/** Az adatbázis egybevetése */
define('DB_COLLATE', '');

/**#@+
 * Bejelentkezést tikosító kulcsok
 *
 * Változtassuk meg a lenti konstansok értékét egy-egy tetszóleges mondatra.
 * Generálhatunk is ilyen kulcsokat a {@link http://api.wordpress.org/secret-key/1.1/ WordPress.org titkos kulcs szolgáltatásával}
 * Ezeknek a kulcsoknak a módosításával bármikor kiléptethető az összes bejelentkezett felhasználó az oldalról. 
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'írjunk ide valami nagyon bonyolultat');
define('SECURE_AUTH_KEY', 'írjunk ide valami nagyon bonyolultat');
define('LOGGED_IN_KEY', 'írjunk ide valami nagyon bonyolultat');
define('NONCE_KEY', 'írjunk ide valami nagyon bonyolultat');
define('AUTH_SALT',        'írjunk ide valami nagyon bonyolultat');
define('SECURE_AUTH_SALT', 'írjunk ide valami nagyon bonyolultat');
define('LOGGED_IN_SALT',   'írjunk ide valami nagyon bonyolultat');
define('NONCE_SALT',       'írjunk ide valami nagyon bonyolultat');

/**#@-*/

/**
 * WordPress-adatbázis tábla előtag.
 *
 * Több blogot is telepíthetünk egy adatbázisba, ha valamennyinek egyedi
 * előtagot adunk. Csak számokat, betűket és alulvonásokat adhatunk meg.
 */
$table_prefix  = 'wp_';

/**
 * WordPress nyelve. Ahhoz, hogy magyarul működjön az oldal, ennek az értéknek
 * 'hu_HU'-nak kell lennie. Üresen hagyva az oldal angolul fog megjelenni.
 *
 * A beállított nyelv .mo fájljának telepítve kell lennie a wp-content/languages
 * könyvtárba, ahogyan ez a magyar telepítőben alapértelmezetten benne is van.
 *  
 * Például: be kell másolni a hu_HU.mo fájlokat a wp-content/languages könyvtárba, 
 * és be kell állítani a WPLANG konstanst 'hu_HU'-ra, 
 * hogy a magyar nyelvi támogatást bekapcsolásra kerüljön.
 */

define ('WPLANG', 'hu_HU');

/**
 * Fejlesztőknek: WordPress hibakereső mód.
 *
 * Engedélyezzük ezt a megjegyzések megjelenítéséhez a fejlesztés során. 
 * Erősen ajánlott, hogy a bővítmény- és sablonfejlesztők használják a WP_DEBUG
 * konstansot.
 */
define('WP_DEBUG', false);
define('RELOCATE',true);
/* Ennyi volt, kellemes blogolást! */

/** A WordPress könyvtár abszolút elérési útja. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Betöltjük a WordPress változókat és szükséges fájlokat. */
require_once(ABSPATH . 'wp-settings.php');
