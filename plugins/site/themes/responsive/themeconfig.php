<?php
Core::stopDirectAccess();
if (!isset($_SESSION)) {
    session_start();
}

/* ===== LIBRARIES USED THROUGHOUT THE THEME */
/* CDNJS - v3.3.7 - BOOTSTRAP */
define('CSS_BOOTSTRAP', SITE_URL . 'vendor/twbs/bootstrap/dist/css/bootstrap.min.css');
define('CSS_BOOTSTRAP_THEME', SITE_URL . 'vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css');
define('JS_BOOTSTRAP', SITE_URL . 'vendor/twbs/bootstrap/dist/js/bootstrap.min.js');
