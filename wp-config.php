<?php
define( 'WP_CACHE', true );




/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u961915702_z2eXx' );

/** Database username */
define( 'DB_USER', 'u961915702_7Hrjz' );

/** Database password */
define( 'DB_PASSWORD', 'YY4b6oBcjw' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '8i:=xx!5p1T*0RDP_>FLQX-W17d^$5R<^gKAq+k@14^WbX5.hSfiL,t&RgApk%84' );
define( 'SECURE_AUTH_KEY',   ']i)JI}kG*7gWud[G;/V,o~>7lwK+<]c7dcWxV^cy|Wsrk$!nO*>H3Ir ~5&WGSv|' );
define( 'LOGGED_IN_KEY',     '88H8VO.a&uWK#xYm3n/9T7D?ZIOosVZE<8)7a9d[e1NJc3H2SA^dJ[{t3Osfg6ci' );
define( 'NONCE_KEY',         '3fl(a~9]p!%2yj9+?at;qWPPwmWzfV> C+01SsmLc7e:vAW#UA22iGJ:Ab&|,m9<' );
define( 'AUTH_SALT',         '`;8D*{CiL^Z14@mzs.g t 5%MY77>?xw`HKa=|udy)@]=-I$s%H89B:Wzx}@xZ,!' );
define( 'SECURE_AUTH_SALT',  '0XTNRKd-(?#%=o5R^!OVNFLGwt.F&]eGS]3=OCzNbak!k01wc sV x:w7#{)RnSX' );
define( 'LOGGED_IN_SALT',    'DHwm:GFqyL/dN~i~(4IUM);n`<@bhcxv^bmsyBaN!Jyxd1j3bfhsx`Oa7]KX#QmH' );
define( 'NONCE_SALT',        'y!:d?0j59$SfQ8^K_>`)p<1-dO[%i#ocqtOs>BJ}J74ZQRa+-Q)W&IW;z{zg=KNi' );
define( 'WP_CACHE_KEY_SALT', '!2ohCfq|`^HawVf;7F](&b7sE$jlk*=DEnLwkqD7<O;+NF4i+G~<qKNye4As!w8W' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', 'c81f6a9e3e3d8766db73df1a2eac6763' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
