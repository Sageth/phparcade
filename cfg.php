<?php

require_once __DIR__ . '/vendor/autoload.php';

/* ******* END INI SETTINGS ******* */
$dbconfig = PHPArcade\Core::getDBConfig();

$inicfg = PHPArcade\Core::getINIConfig();
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
define('EXT_IMG', '.png');
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

/* ===== LIBRARIES USED THROUGHOUT THE SITE
   ===== YOU CAN FIND THEME-SPECIFIC CONSTANTS IN THE themeconfig.php FILE IN EACH THEME */

/* CDNJS - v4.1.1 - BOOTSTRAP */
define('CSS_BOOTSTRAP_ADMIN', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css');
define('CSS_BOOTSTRAP_ADMIN_SRI', 'sha256-Md8eaeo67OiouuXAi8t/Xpd8t2+IaJezATVTWbZqSOw=');

define('JS_BOOTSTRAP_ADMIN', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js');
define('JS_BOOTSTRAP_ADMIN_SRI', 'sha256-xaF9RpdtRxzwYMWg4ldJoyPWqyDPCRD0Cv7YEEe6Ie8=');

/* CDNJS - v2.2.2 - BOOTSTRAP TOGGLE */
define('CSS_BOOTSTRAP_TOGGLE', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css');
define('CSS_BOOTSTRAP_TOGGLE_SRI', 'sha256-rDWX6XrmRttWyVBePhmrpHnnZ1EPmM6WQRQl6h0h7J8=');

define('JS_BOOTSTRAP_TOGGLE', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js');
define('JS_BOOTSTRAP_TOGGLE_SRI', 'sha256-eZNgBgutLI47rKzpfUji/dD9t6LRs2gI3YqXKdoDOmo=');

/* CDNJS - v4.7.0 - FONT AWESOME */
define('CSS_FONTAWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
define('CSS_FONTAWESOME_SRI', 'sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=');

/* CUSTOM - INPUT COLORS */
define('CSS_INPUTCOLORS', SITE_URL_ADMIN . 'assets/css/inputcolors.min.css');

/* GOOGLE RECAPTCHA */
define('JS_GOOGLE_RECAPTCHA', 'https://www.google.com/recaptcha/api.js');

/* CDNJS - v3.3.1 - JQUERY */
define('JS_JQUERY', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');
define('JS_JQUERY_SRI', 'sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=');

/* CDNJS - v1.12.1 - JQUERY UI */
define('JS_JQUERY_UI', SITE_URL . 'vendor/components/jqueryui/jquery-ui.min.js');

/* CDNJS - v10.6.0 - VANILLA-LAZYLOAD */
define('JS_LAZYLOAD', 'https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.8.0/lazyload.min.js');
define('JS_LAZYLOAD_SRI', 'sha256-Oz+eDj3BmLJZdPBBQ+aooOaZuZ516P87TlmDY/yHHT8=');

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

/* Set Timezone */
date_default_timezone_set('America/New_York');

/* Enable debug logging in non-prod */



