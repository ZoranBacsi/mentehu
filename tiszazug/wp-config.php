<?php

define( 'BWPS_FILECHECK', true );

/**
* The base configurations of the WordPress.
*
* This file has the following configurations: MySQL settings, Table Prefix,
* Secret Keys, WordPress Language, and ABSPATH. You can find more information
* by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
* wp-config.php} Codex page. You can get the MySQL settings from your web host.
*
* This file is used by the wp-config.php creation script during the
* installation. You don't have to use the web site, you can just copy this file
* to "wp-config.php" and fill in the values.
*
* @package WordPress
*/

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/menteh01/public_html/tiszazug/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'menteh01_wp365tz');

/** MySQL database username */
define('DB_USER', 'menteh01_wp365tz');

/** MySQL database password */
define('DB_PASSWORD', 'x9azSpP239');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
* Authentication Unique Keys and Salts.
*
* Change these to different unique phrases!
* You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
* You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*
* @since 2.6.0
*/
define('AUTH_KEY',         'oh2bycxwgxtpmtlooc3cnfnpfouoevfylmj0vqqhluiuhmfwwgpifppldeiavbuq');
define('SECURE_AUTH_KEY',  'acn4rzpkqeogxofmkbsvxmibtjuntf6gg4lzq3gy1gw2qjeve1fhamymdiajr4s9');
define('LOGGED_IN_KEY',    'f9mvqk0altqls1jp1uszk67jmzkdprfqctm13xyeecbw4dhnngmtcnbaw25wbqmr');
define('NONCE_KEY',        'bsyrb4tovoxzfogqewhgzdeviuvr6rowxe5ruj8nujbjab7qbkxbnfs2kje3irpz');
define('AUTH_SALT',        'd87mq9tajipesu4tbqh4mu23x3m8dtrjzn4yb60gst7lxyg5qctwz5vp7mmusyql');
define('SECURE_AUTH_SALT', 'vz5mhiu3xcycklxekskbrb2x5js5dko7d8z8ycq0okxvb1kf4tesf76hdtvdi7kk');
define('LOGGED_IN_SALT',   'mismyrqtpk1s34mpcheq9lmrmg5kfb06pj38d3yfpjyfukkfyov2in1eynzcopoi');
define('NONCE_SALT',       'zcjcmwtnj0jpw9958mtdc4cl0jhridg5ao9iwjwqotrzh9kgfsemuyiw7kqgpv5m');

/**#@-*/

/**
* WordPress Database Table prefix.
*
* You can have multiple installations in one database if you give each a unique
* prefix. Only numbers, letters, and underscores please!
*/
$table_prefix  = 'wptz_';

/**
* WordPress Localized Language, defaults to English.
*
* Change this to localize WordPress.  A corresponding MO file for the chosen
* language must be installed to wp-content/languages. For example, install
* de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
* language support.
*/
define ('WPLANG', 'hu_HU');

/**
* For developers: WordPress debugging mode.
*
* Change this to true to enable the display of notices during development.
* It is strongly recommended that plugin and theme developers use WP_DEBUG
* in their development environments.
*/
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
