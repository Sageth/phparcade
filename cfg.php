<?php
/* Prevent direct access */
if (count(get_included_files()) === 1) {http_response_code(403);die('Direct access not permitted.');}

/* Load Classes before setting the constants */
spl_autoload_register('phpArcadeClasses');
function phpArcadeClasses($class_name) {
    /** @noinspection PhpIncludeInspection */
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/classes/' . $class_name . '.php';
}

/* ******* START INI SETTINGS ******* */

/* Session params - keep session data for AT LEAST 1 hour (60s * 60m)*/
ini_set('session.gc_maxlifetime', 3600);

/* Set Secure cookie if HTTPS */
if (Administrations::getScheme() === 'https://') {
    ini_set('session.cookie_secure', 1);
}

/* Set cookie to http only */
ini_set('session.cookie_httponly', 1);

/* Set Timezone */
date_default_timezone_set('America/New_York');

/* Enable debug logging in non-prod */
$inicfg = Core::getINIConfig();
if ($inicfg['environment']['state'] === "dev"){
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

/* ******* END INI SETTINGS ******* */
$dbconfig = Core::getInstance()->getDBConfig();

/* Define site constants */
define('INST_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
define('IMG_DIR', INST_DIR . 'img/');
define('IMG_DIR_NOSLASH', INST_DIR . 'img');
define('SITE_URL', sprintf('%s://%s/', isset($_SERVER['HTTPS']) &&
                                       $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME']));
define('SITE_URL_ADMIN', SITE_URL . 'Zdmin/');
define('SWF_DIR', INST_DIR . 'swf/');
define('SWF_URL', SITE_URL . 'swf/');
define('SITE_THEME_DIR', SITE_URL . 'plugins/site/themes/' . $dbconfig['theme'] . '/');
define('CHARSET', 'UTF-8');
define('TOP_SCORE_COUNT', 10);

/* ===== LIBRARIES USED THROUGHOUT THE SITE */

/* CDNJS - v3.3.7 - BOOTSTRAP */
define('CSS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css');
define('CSS_BOOTSTRAP_THEME', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap-theme.min.css');
define('JS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js');

/* CDNJS - v2.2.2 - BOOTSTRAP TOGGLE */
define('CSS_BOOTSTRAP_TOGGLE', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css');
define('JS_BOOTSTRAP_TOGGLE', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js');

/* CDNJS - v4.7.0 - FONT AWESOME */
define('CSS_FONTAWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

/* GOOGLE RECAPTCHA */
define('JS_GOOGLE_RECAPTCHA', 'https://www.google.com/recaptcha/api.js');

/* CDNJS - v3.2.1 - JQUERY */
define('JS_JQUERY', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js');

/* CDNJS - v1.12.1 - JQUERY UI */
define('JS_JQUERY_UI', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');

/* CDNJS - v2.7.0 - JQUERY METISMENU */
define('CSS_METISMENU', 'https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.css');
define('JS_METISMENU', 'https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js');

/* CDNJS - v3.3.7+1 SB ADMIN 2 THEME */
define('CSS_SB_ADMIN_2', 'https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/3.3.7+1/css/sb-admin-2.min.css');
define('JS_SB_ADMIN_2', 'https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/3.3.7+1/js/sb-admin-2.min.js');

/* CDNJS - v2.2.0 - SWFOBJECT */
define('JS_SWFOBJECT', 'https://cdnjs.cloudflare.com/ajax/libs/swfobject/2.2/swfobject.min.js');

/* CUSTOM - TABLESORT */
define('JS_TABLESORT', SITE_URL . 'plugins/site/themes/admin/assets/js/tablesort.min.js');

/* STANDARD URLS FOR EXTERNAL SITES */
define('URL_FACEBOOK', 'https://www.facebook.com/');
define('URL_GITHUB', 'https://github.com/');
define('URL_GITHUB_PHPARCADE', 'https://github.com/Sageth/phpArcade/');
define('URL_TWITTER', 'https://www.twitter.com/');
/* ====== END CONSTANTS ===== */

/* Load vendor/composer downloaded functions */
require_once INST_DIR . 'vendor/autoload.php';
