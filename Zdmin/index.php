<?php
$adminarea = true;

/* Always show errors in Admin */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/* End Show Errors */

require_once __DIR__ . '/../cfg.php';
define('JS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js');

// global vars
$act = $_REQUEST['act'] ?? 'site';
// -----------

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
ob_start('PHPArcade\Administrations::admin_set_content');
$func = $act . '_admin';
if (function_exists($func)) {
    $mthd = $_REQUEST['mthd'] ?? "";
    $func($mthd);
}
ob_end_flush();
PHPArcade\Core::doEvent('admin_theme_display');
/** @noinspection PhpIncludeInspection */
/** @noinspection PhpUndefinedVariableInspection */
require_once ADMIN_SITE_THEME_PATH;
