<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', 'daugiahoanggiadb');

/** MySQL database username */
define('DB_USER', 'daugiahoanggiauser');

/** MySQL database password */
define('DB_PASSWORD', 'Daugiahoanggia@123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'Ki|$Oo qg%R/AfIpZkzM!DodEpMm*OyU{2SWFf/ml59s]Q_) %N|<bH+/+mx*fHJ');
define('SECURE_AUTH_KEY',  '|mPJ[gu|-;0c@.tg60V{6Jd:>$Dmw#s{Z[B$asgq`ObubaD@PTPo^g=1F7,} I5=');
define('LOGGED_IN_KEY',    'eonxgL6vKVN ?vD*xtuqX59ev3xx6LA T )z;ga*4>OO`O_Y!d d7d.B*8|-ym#:');
define('NONCE_KEY',        'jh}Usyv0sDr|N(A;*KcsNE!t!K[pXhtl#`,LR$sgqI_mQAkVQtrctBje)M{mZe@%');
define('AUTH_SALT',        '=T+5%d=<q7]E <;ac+f(7!r?Mw!6dK?Tw&foPP3a(KB)}S|HF|L@/8nP^o{+5OY+');
define('SECURE_AUTH_SALT', '=uT`mNM*m7htb&rRI7vwN7>] ]5OAiuggV`RZ~O_RI~1@tz_ ~k>)!z=|APda+Yj');
define('LOGGED_IN_SALT',   'W_X^{?T{+2%6$4-QFhTD6*vk&E`(%u5Sv}A*q~ZG6~IK`Gt_>WL|Mh>Gy0tpZWMH');
define('NONCE_SALT',       'wb9#knHA&y:4W,@=2MrNlCBX_`*<0inzST+z2+{C,Am`4Qy-WaJ@6k8G;-Tq{N>f');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mcs_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
