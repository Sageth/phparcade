<?php
/* Prevent direct access */
if (count(get_included_files()) === 1) {
    http_response_code(403);
    die('Direct access not permitted.');
}

/* Load Classes before setting the constants */
spl_autoload_register('phpArcadeClasses');
function phpArcadeClasses($class_name)
{
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
$inicfg = Core::getInstance()->getINIConfig();
if ($inicfg['environment']['state'] === "dev") {
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
define('ADMIN_THEME_PATH', INST_DIR . 'Zdmin/index.php');
define('ADMIN_SITE_THEME_PATH', INST_DIR . 'plugins/site/themes/admin/index.php');
define('CHARSET', 'UTF-8');
define('EXT_IMG', '.png');
define('SITE_META_DESCRIPTION', $dbconfig['metadesc']);
define('SITE_META_KEYWORDS', $dbconfig['metakey']);
define('SITE_META_TITLE', $dbconfig['sitetitle']);
define('SITE_THEME_URL', SITE_URL . 'plugins/site/themes/' . $dbconfig['theme'] . '/');
define('SITE_THEME_PATH', INST_DIR . 'plugins/site/themes/' . $dbconfig['theme'] . '/index.php');
define('SITE_URL_ADMIN', SITE_URL . 'Zdmin/');
define('SWF_DIR', INST_DIR . 'swf/');
define('SWF_URL', SITE_URL . 'swf/');
define('TOP_SCORE_COUNT', 10);

/* ===== LIBRARIES USED THROUGHOUT THE SITE */

/* CDNJS - v3.3.7 - BOOTSTRAP */
define('CSS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css');
define('CSS_BOOTSTRAP_SRI', 'sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=');

define('CSS_BOOTSTRAP_THEME', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap-theme.min.css');
define('CSS_BOOTSTRAP_THEME_SRI', 'sha256-ZT4HPpdCOt2lvDkXokHuhJfdOKSPFLzeAJik5U/Q+l4=');

define('JS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js');
define('JS_BOOTSTRAP_SRI', 'sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=');

/* CDNJS - v2.2.2 - BOOTSTRAP TOGGLE */
define('CSS_BOOTSTRAP_TOGGLE', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css');
define('CSS_BOOTSTRAP_TOGGLE_SRI', 'sha256-rDWX6XrmRttWyVBePhmrpHnnZ1EPmM6WQRQl6h0h7J8=');

define('JS_BOOTSTRAP_TOGGLE', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js');
define('JS_BOOTSTRAP_TOGGLE_SRI', 'sha256-eZNgBgutLI47rKzpfUji/dD9t6LRs2gI3YqXKdoDOmo=');

/* CDNJS - v4.7.0 - FONT AWESOME */
define('CSS_FONTAWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
define('CSS_FONTAWESOME_SRI', 'sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=');

/* GOOGLE RECAPTCHA */
define('JS_GOOGLE_RECAPTCHA', 'https://www.google.com/recaptcha/api.js');

/* CDNJS - v3.2.1 - JQUERY */
define('JS_JQUERY', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js');
define('JS_JQUERY_SRI', 'sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=');

/* CDNJS - v1.12.1 - JQUERY UI */
define('JS_JQUERY_UI', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
define('JS_JQUERY_UI_SRI', 'sha256-KM512VNnjElC30ehFwehXjx1YCHPiQkOPmqnrWtpccM=');

/* CDNJS - v2.7.0 - JQUERY METISMENU */
define('CSS_METISMENU', 'https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.css');
define('CSS_METISMENU_SRI', 'sha256-OufFdETrXbY5YtILsSTzlY+4Ttmq+hUfR1mMYLmKlWo=');

define('JS_METISMENU', 'https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js');
define('JS_METISMENU_SRI', 'sha256-EdeVuolWxmuLTapvaUaXTYIYKTIlhc0nUEGPEMn8MhQ=');

/* CDNJS - v8.0.3 - VANILLA-LAZYLOAD */
define('JS_LAZYLOAD', 'https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/8.0.3/lazyload.min.js');
define('JS_LAZYLOAD_SRI', 'sha256-Vd2fw5d0r2jU7TDWtmhCfzsauG213/Ns3xeVcxQPS1o=');

/* CDNJS - v3.3.7+1 SB ADMIN 2 THEME */
define('CSS_SB_ADMIN_2', 'https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/3.3.7+1/css/sb-admin-2.min.css');
define('CSS_SB_ADMIN_2_SRI', 'sha256-WeMGw+d+qR+l2h9TzmC+jTME4zy5zYzG8E6FbPikzeM=');

define('JS_SB_ADMIN_2', 'https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/3.3.7+1/js/sb-admin-2.min.js');
define('JS_SB_ADMIN_2_SRI', 'sha256-Y0Z5pT4qPGaoUSHoxW+J8fIWjQnjc7v03WBEUnt9SQ0=');

/* CDNJS - v2.2.0 - SWFOBJECT */
define('JS_SWFOBJECT', 'https://cdnjs.cloudflare.com/ajax/libs/swfobject/2.2/swfobject.min.js');
define('JS_SWFOBJECT_SRI', 'sha256-oYy9uw+7cz1/TLpdKv1rJwbj8UHHQ/SRBX5YADaM2OU=');

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
