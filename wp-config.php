<?php
define( 'WP_CACHE', false ); // By Speed Optimizer by SiteGround

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
define( 'DB_NAME', 'dbjbgffmxtothh' );

/** Database username */
define( 'DB_USER', 'umg0w4zqkerhm' );

/** Database password */
define( 'DB_PASSWORD', 'fcvkfhmpnu2h' );

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
define( 'AUTH_KEY',          '/F~@?-)/W&?Zezi&prPH7>ufJQ*8+qTgfy6}Y.G$P&dFb/4NzFhU&ctX],92Am_H' );
define( 'SECURE_AUTH_KEY',   '{^B;/#Pi/rilbh.PVNGJ_2Qw9m4>,(;/C&]VU+}YygZmU%bQS8a=_;sfR1O/TTm:' );
define( 'LOGGED_IN_KEY',     '-%[7yjNFHF]G/hHF#e.vQ4z}36O)>gu_ePy[PbcDV@8bSXzTr?7Ng(_*]<e?v:oU' );
define( 'NONCE_KEY',         'M^ObO0oMjQ:X)hOL99KJ~O^@[o1sG&IJOw uT7@e8DafS(@=cE@+Kr0DK`(J`+L{' );
define( 'AUTH_SALT',         'nam46j>-wy-=X2SO<HxR4E|-|q0gd~_:NtR^X5ST6x*H~_7_;+G_@9!hlx:wwNxd' );
define( 'SECURE_AUTH_SALT',  'J`,oum-N4[ly3|O.YVkJRY4Zph,p`t4wY!+.PHA@vpxyOK$w&8sz3o?W36vPuE~m' );
define( 'LOGGED_IN_SALT',    ':)6$`3b173;1R(R>z}EGz*Yj}.Jz/LCNw[=mz6vS0G-mp|</6ZV}+jtKZ7}AO1fN' );
define( 'NONCE_SALT',        '%vO)!qw0+zaIE12(<Yf},X-Hom eYPL;1ph^c&]]5aTh]&;k=60IC|2v<9Sm^F6?' );
define( 'WP_CACHE_KEY_SALT', 'cDnn;8> De%&{Fq5Ob8,MhE?X&Y%}?mY[?-SWwBoXn:<hzKU(33&[j0%jjWlu{>X' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'qxv_';


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

define( 'AS3CF_SETTINGS', serialize( array(
	'provider' => 'aws',
	'access-key-id' => 'AKIA4Z7K5PXJIQXK2HXC',
	'secret-access-key' => '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',
) ) );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
