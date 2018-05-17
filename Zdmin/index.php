<?php
$adminarea = true;

/* Always show errors in Admin */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/* End Show Errors */

require_once __DIR__ . '/../cfg.php';
PHPArcade\Languages::loadLanguage();

define('JS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js');

// global vars
$act = $_REQUEST['act'] ?? 'site';
// -----------
?>

<?php
PHPArcade\Core::stopDirectAccess();
if (!isset($_SESSION)) {
    session_start();
}
global $links, $linkshref, $sublinkshref, $sublinks;
$content = $content ?? ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?php echo CHARSET; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php
        echo gettext('logo');
        echo gettext('admin'); ?>
    </title>
    <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP_ADMIN; ?>"/>
    <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP_TOGGLE; ?>"/>
    <link rel="stylesheet" href="<?php echo CSS_FONTAWESOME; ?>"/>
    <link rel="stylesheet" href="<?php echo CSS_INPUTCOLORS;?>"/>
</head>
<body>
    <?php include (__DIR__ . '/admin_nav.php'); ?>
    <p class="mt-4">&nbsp;</p>
    <?php
    $dh = opendir('../plugins');
    while (($filename = readdir($dh)) !== false) {
        $files[] = $filename;
    }
    sort($files);
    $arr = [];
    foreach ($files as $file) {
        switch ($file) {
            case '.':
            case '..':
                continue 2;
        }
        if ($file[0] != '~') {
            $arr[] = $file;
        }
    }
    include INST_DIR . 'includes/first.php';
    foreach ($arr as $plugin) {
        if (file_exists('../plugins/' . $plugin . '/admin.php')) {
            /** @noinspection PhpIncludeInspection */
            require_once '../plugins/' . $plugin . '/admin.php';
            $func = $plugin . '_links';
            $func();
        }
    }

    // Make sure the user is logged in.
    $dbconfig = PHPArcade\Core::getDBConfig();
    if ($dbconfig['membersenabled'] === 'on') {
        if (!isset($_SESSION)) {
            session_start();
        }
        $user = $_SESSION['user'];
        if ($user === false || $user['admin'] != 'Yes') {
            header('Location: ' . SITE_URL);
            exit();
        }
    }
    $func = $act . '_admin';
    if (function_exists($func)) {
        $mthd = $_REQUEST['mthd'] ?? "";
        $func($mthd);
    }
    PHPArcade\Core::doEvent('admin_theme_display');
    /** @noinspection PhpIncludeInspection */
    /** @noinspection PhpUndefinedVariableInspection */
    require_once ADMIN_SITE_THEME_PATH; ?>
    <div class="container">
        <div class="row">
            <?php echo $content; ?>
        </div>
    </div>
    <script src="<?php echo JS_JQUERY; ?>" defer></script>
    <script src="<?php echo JS_JQUERY_UI; ?>" defer></script>
    <script src="<?php echo JS_TABLESORT; ?>" defer></script>
    <script src="<?php echo JS_BOOTSTRAP_ADMIN; ?>" defer></script>
    <script src="<?php echo JS_BOOTSTRAP_TOGGLE; ?>" integrity="<?php echo JS_BOOTSTRAP_TOGGLE_SRI;?>" crossorigin="anonymous" defer></script>
</body>
</html>