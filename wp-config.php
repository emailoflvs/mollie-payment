<?php
$_SERVER['HTTPS'] = 'on'; //MY SCRIPT login to admin panel
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'prizeme.de' );

/** MySQL database username */
define( 'DB_USER', 'prizeme.de' );

/** MySQL database password */
define( 'DB_PASSWORD', '1Q7j0B3s' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         's(IYZ/-<r%K8xb<z3gP&*gyL`.bs5!tE<:`X7@%|dDkLqYJat:M/o!2r2;2{@1&n' );
define( 'SECURE_AUTH_KEY',  'rR-/5B@H/#!gD5~y=OBn]MXG5iWv`J:5=NC%?uOiQrtUWIOh`9~{bTPJ|>Lt5<iu' );
define( 'LOGGED_IN_KEY',    'MpXxsDb;`$VH1xsKhq1+%|T_cjY<_vk:wp[Xv (v(IEpoom}]:{eZY`FIvdEwl7W' );
define( 'NONCE_KEY',        'C&G;BKDW6W_mP`/,<HPfK3HF+Xt7w3}k-{<*/?bU>l9U@WY|hZI(Bt~ob%IU+t+%' );
define( 'AUTH_SALT',        'WC|D_%Sk{yq[rHj3MFi&uSIm1W8SzO8 **+KQ8^} T|7~#zX:p`h&JWX<[(t8<!-' );
define( 'SECURE_AUTH_SALT', 'ADe`u?h 3{1^~s2ujW=q`#72Onv=,W6nRk]DI5B2#aG>X&!4V`{ID<DRNnL!.}s0' );
define( 'LOGGED_IN_SALT',   'dZJ_[HO82*7GoR)I%~({Xk4~zv3n<Vce4/U4_W !GT-G>0cde-6yD5d^y#*JleEx' );
define( 'NONCE_SALT',       'reT<~#Y1?|_tzif %j0T?zf;|2^o.~:[9%s4^Q/@7aq4;J&@V$~O;!##k~{TPBZ9' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'pd_';

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
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
