<?php

require_once __DIR__ . '/vendor/autoload.php';

/* ******* END INI SETTINGS ******* */
$dbconfig = PHPArcade\Core::getDBConfig();

$inicfg = PHPArcade\Core::getINIConfig();

/* Enable debug logging in non-prod */
if ($inicfg['environment']['state'] === "dev") {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}


/* Define site constants */
define('INST_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
define('IMG_DIR', INST_DIR . 'img/');
define('IMG_DIR_NOSLASH', INST_DIR . 'img');
define('SITE_URL', sprintf('%s://%s/', isset($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME']));
define('CHARSET', 'UTF-8');
define('EXT_IMG', '.webp');
define('EXT_IMG_MIMETYPE', 'image/webp');
define('FONT_AWESOME_KIT', '98619fa94d');
define('GRAVATAR_URL', 'https://www.gravatar.com/avatar/');
define('IMG_URL', SITE_URL . 'img/');
define('SITE_META_DESCRIPTION', $dbconfig['metadesc']);
define('SITE_META_KEYWORDS', $dbconfig['metakey']);
define('SITE_META_TITLE', $dbconfig['sitetitle']);
define('SITE_THEME_URL', SITE_URL . 'plugins/site/themes/' . $dbconfig['theme'] . '/');
define('SITE_THEME_PATH', INST_DIR . 'plugins/site/themes/' . $dbconfig['theme'] . '/index.php');
define('SITE_URL_ADMIN', SITE_URL . 'Zdmin/');
define('SWF_DIR', INST_DIR . 'swf/');
define('SWF_URL', SITE_URL . 'swf/');
define('TOP_SCORE_COUNT', 10);

/* Parameter constants */
define('P_ADID', ':adid');
define('P_CONTENT', 'content');
define('P_DESCRIPTION', 'description');
define('P_GAMES', 'Games');
define('P_KEYWORDS', 'keywords');
define('P_LOCATION', 'location');
define('P_NAME', '%name%');
define('P_SCORE', 'score');
define('P_TITLE', 'title');


/* ===== LIBRARIES USED THROUGHOUT THE SITE
   ===== YOU CAN FIND THEME-SPECIFIC CONSTANTS IN THE themeconfig.php FILE IN EACH THEME */

/* CDNJS - BOOTSTRAP */
define('CSS_BOOTSTRAP_ADMIN', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css');
define('CSS_BOOTSTRAP_ADMIN_SRI', 'sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=');

define('JS_BOOTSTRAP_ADMIN', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js');
define('JS_BOOTSTRAP_ADMIN_SRI', 'sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=');

/* CDNJS BOOTSTRAP TOGGLE */
define('CSS_BOOTSTRAP_TOGGLE', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css');
define('CSS_BOOTSTRAP_TOGGLE_SRI', 'sha256-rDWX6XrmRttWyVBePhmrpHnnZ1EPmM6WQRQl6h0h7J8=');

define('JS_BOOTSTRAP_TOGGLE', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js');
define('JS_BOOTSTRAP_TOGGLE_SRI', 'sha256-eZNgBgutLI47rKzpfUji/dD9t6LRs2gI3YqXKdoDOmo=');

/* CUSTOM - INPUT COLORS */
define('CSS_INPUTCOLORS', SITE_URL_ADMIN . 'assets/css/inputcolors.min.css');

/* GOOGLE RECAPTCHA */
define('JS_GOOGLE_RECAPTCHA', 'https://www.google.com/recaptcha/api.js');

/* CDNJS - JQUERY */
define('JS_JQUERY', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js');
define('JS_JQUERY_SRI', 'sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=');


/* CDNJS - JQUERY UI */
define('JS_JQUERY_UI', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
define('JS_JQUERY_UI_SRI', 'sha256-KM512VNnjElC30ehFwehXjx1YCHPiQkOPmqnrWtpccM=');

/* CDNJS - VANILLA-LAZYLOAD */
define('JS_LAZYLOAD', 'https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/12.0.3/lazyload.min.js');
define('JS_LAZYLOAD_SRI', 'sha256-07HbhWMzlSuhFpeiTy8zouvXxdqnEZ63A4KBLW2Efqc=');

/* CDNJS - v2.2.0 - SWFOBJECT */
define('JS_SWFOBJECT', 'https://cdnjs.cloudflare.com/ajax/libs/swfobject/2.2/swfobject.min.js');
define('JS_SWFOBJECT_SRI', 'sha256-oYy9uw+7cz1/TLpdKv1rJwbj8UHHQ/SRBX5YADaM2OU=');

/* CUSTOM - TABLESORT */
define('JS_TABLESORT', SITE_URL_ADMIN . 'assets/js/tablesort.min.js');

/* CUSTOM - USERFILTER */
define('JS_TABLEFILTER', SITE_URL_ADMIN . '/assets/js/tablefilter.min.js');

/* STANDARD URLS FOR EXTERNAL SITES */
define('URL_FACEBOOK', 'https://www.facebook.com/');
define('URL_GITHUB', 'https://github.com/');
define('URL_GITHUB_PHPARCADE', 'https://github.com/Sageth/phpArcade/');
define('URL_TWITTER', 'https://www.twitter.com/');

define('CSS_DANGER', 'danger');
define('CSS_SUCCESS', 'success');
/* ====== END CONSTANTS ===== */


/* ******* START INI SETTINGS ******* */

/* Session params - keep session data for AT LEAST 1 hour (60s * 60m)*/
ini_set('session.gc_maxlifetime', 3600);

/* Set Secure cookie if HTTPS */
if (PHPArcade\Administrations::getScheme() === 'https://') {
    ini_set('session.cookie_secure', 1);
}

/* Set cookie to http only */
ini_set('session.cookie_httponly', 1);

/* Set cookie to SameSite Lax */
/* https://www.php.net/manual/en/session.configuration.php#ini.session.cookie-samesite */
ini_set('session.cookie_samesite', 'Lax');

/* Set Timezone */
date_default_timezone_set('America/New_York');
