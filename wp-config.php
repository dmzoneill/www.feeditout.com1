<?php
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
define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', 'fiowordpress');

/** MySQL database username */
define('DB_USER', 'books');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'mysql.feeditout.com');

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
define('AUTH_KEY',         'NU}Vg<frHt^=kA[2%1&%(ui&?1CdeYHq|04$2Wz1r-a4 bhOJ+I)7ZYb b0L[nfM');
define('SECURE_AUTH_KEY',  '//+-hOO%!]!`+|YtDiC;MrV=6%?,[X%Dj{vVFgH}E7;dT}M`ChdbYVT1`|H  fg@');
define('LOGGED_IN_KEY',    '+e$.9h0=|P4F {DLNa>0iq6x$lmwojR@xm8+MV%)>`sAQ<vBK. 26P[JUyU|oRy^');
define('NONCE_KEY',        '<~e |-a YMx.|?YwjryR-=jdB@E4.aKq]02lRRKN%og1%%^`QuA;~b=[Xd;L!U/I');
define('AUTH_SALT',        '1L|lAx3&DoM*~-/%YYQs#M/%cR8d.$/*M.mO/Mxt,eS-Dj-SEd& Tve` .b0Aw6J');
define('SECURE_AUTH_SALT', 'S*8vE2n{/):s+AHWvQ}f=xC5OxJd Kg|b*Y r|z`oYj0${1Z%FV=6[<qqfq.dgJ!');
define('LOGGED_IN_SALT',   'wW>|Q,6El%}R$nZ*#)Js~~ 1`g=o_tOA,K]g=A<OP~#Zn/r4H:-9[xvqf70qzDxy');
define('NONCE_SALT',       'rwwC!]u|I?(E(XdLBS.Hhv7afXA%qJg16O-A2@CwGvdiFMzxA%xOVncn{XXh-gi@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpfio_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
