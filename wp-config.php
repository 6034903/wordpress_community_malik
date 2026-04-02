<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_community_malik' );

/** Database username */
define( 'DB_USER', 'malik123' );

/** Database password */
define( 'DB_PASSWORD', '123' );

/** Database hostname */
define( 'DB_HOST', 'db' );

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
define( 'AUTH_KEY',         '`,G(5pN4SkXHm*Nz(7GHl %6%.(6]6+0g0UgVwpQjRw/$Oq)S &S<nbBOdUK/|ER' );
define( 'SECURE_AUTH_KEY',  'c5ibap<V;}=ib]SzG(m~e~@UW`axs,K>l!gcq>P|#gcj02*6v$oMCNIXF&2]A~>X' );
define( 'LOGGED_IN_KEY',    '%56RyX>6;<7QHrYIaaP/qZdP6INJz9G %QHJ d2E`i6po9}K}U5hM5Y@iH=-);.&' );
define( 'NONCE_KEY',        'pgb!QeE?_$)r1.W4>9YM2^EuL^~-.!b^7OIc%9D.+SdFcM_NO29o^.Q.T4RJ:Q1g' );
define( 'AUTH_SALT',        't+hAAR)SW9VE?2KEe(Ik^FQ89e#}UfPBfV^076)fQrxjgZH&K^[.q0][Qs4KHg$e' );
define( 'SECURE_AUTH_SALT', 'B6iCQYk;ja;KxwRrh3QU(o[N;McoRYsAMA[)Ega6%GLBm?6F1vYXa%{=/z?O6u5)' );
define( 'LOGGED_IN_SALT',   '_g?_^dkvR[bJV75O#v8i)8m0m@:T+!.Z[gw2vP7`3:}ZK&!k=SHY#(tuxi`d0yl!' );
define( 'NONCE_SALT',       'Q5fNT@]ih`bGG~k#naiY&T3#JBph7{XXlynh.a?sTTk,&qoNuLHj0!xlWHGxl$b5' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
