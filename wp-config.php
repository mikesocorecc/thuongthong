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

/** PHP Memory */
const WP_MEMORY_LIMIT     = '512';
const WP_MAX_MEMORY_LIMIT = '512';

const DISALLOW_FILE_EDIT = false;
const DISALLOW_FILE_MODS = false;

/* SSL */
const FORCE_SSL_LOGIN = true;
const FORCE_SSL_ADMIN = true;

const WP_POST_REVISIONS = 1;
const EMPTY_TRASH_DAYS  = 5;
const AUTOSAVE_INTERVAL = 120;

/** Disable WordPress core auto-update, */
const WP_AUTO_UPDATE_CORE = false;

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

const DB_NAME = 'kt6hdweb24h_thuongthong';
const DB_USER = 'kt6hdweb24h_thuongthong';
const DB_PASSWORD = 'ejh2WYjD!QIGd';
const DB_HOST = 'localhost';
const DB_CHARSET = 'utf8mb4';
const DB_COLLATE = '';

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
define( 'AUTH_KEY',         'DnzyOEr#zQ*`3y-aJT61J~a*}{-UQ:j!!IM12shvnpY&S2An8)B($aiD=e0<,6WA' );
define( 'SECURE_AUTH_KEY',  'Wi6B*^mq;h@[fldKno6Z_#t35:7i1jYUymOQ^dw N5-eSBX8I^RR`~0x7H!0ZaXh' );
define( 'LOGGED_IN_KEY',    'puf3Q?WZw/9E.X[b0E1o5graGP/W/~j>YT3(Y4G$j{{3%UP:mjGUk#8(@8:=&7Yz' );
define( 'NONCE_KEY',        'qQ<|P>)`^w}HfZ._Q17rza^@ cno (ehg EsU{{{&ZZ2]XO0QS8:dy}6VS39]1<i' );
define( 'AUTH_SALT',        '/X3`HhErmt8uz,v{5oRUrA;0&xHE<AS>}1olt&2YR1z6YmN92@?gO*KHQS^xwNuK' );
define( 'SECURE_AUTH_SALT', '&r4f;CUc|wg`WQ2,qO^`}=8u%O7R;n,7lAO2xRiBCma8*.:]0CQ$x!/p@lKRs>2=' );
define( 'LOGGED_IN_SALT',   'a?fPcN2o]_<<A`tZ(=Q_;9|U 0Y!ZHY)`#L}|q},nrFKlbZ[ndcy@ENqY~qWG@7y' );
define( 'NONCE_SALT',       'C1|tmHs0^oENRt,7-FY%D~Dq]^%uN;#QFy)Eu+k6S.3u}${j+XUJxt^d#2.t!NuM' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'w_';

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
const WP_DEBUG = false;

/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';