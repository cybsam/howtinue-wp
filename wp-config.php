<?php
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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', 'C:\xampp\htdocs\howtinue\wp-content\plugins\wp-super-cache/' );
define( 'DB_NAME', 'howtinue_wp' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'o<D]sj]Zxa_g)5bt5b+`o ::;@WRPta}.>/|C_8l?J@[c&rm0lG:$L%#j*DS}?Kz' );
define( 'SECURE_AUTH_KEY',  'c]->7zY:k4_gngC,<fM=2<2*GL{q=1yKj9GwIE[vJt@xSeZt4l.J>c31*V&=U4oU' );
define( 'LOGGED_IN_KEY',    '?$[ gZJLJn/JIzm_QS!D,4I/Qci^}FP5#JXs,c$qD1{tqg4gOva,c|*?EmBB!sYo' );
define( 'NONCE_KEY',        'vL?Rca;prs*_b+3 ^%OBAH8A-yQS6s6-?03~`dLX%N15KH?kpf@89u0o7+;i2Fa#' );
define( 'AUTH_SALT',        ':iKNvztT^4_&Nkl2?fSI_w#4G-B1j#o7XosL7NkwKBlMoP!eCDd%0cT!U^1]H;/@' );
define( 'SECURE_AUTH_SALT', ')p0}!qni^mR_MGAk93!)r#xmOviu~n?6IMYEQ]mXSR T [ 0qAQoLmKpoC|!,aq&' );
define( 'LOGGED_IN_SALT',   'Lf 4DfG7PI;~U;QhDD5,a#* `Oh,~Z;<-;ZFNE`Z}L ?qx7zIz-eo17vkp:TpM.P' );
define( 'NONCE_SALT',       '!hB,ESvt*S`[Xfr2vQ<&q5%Qku3zjn|&O~rmoY|vig4Z&9x5f=.=M n-jM7|!4u-' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
