<?php
PHPArcade\Core::stopDirectAccess();
if (!isset($_SESSION)) {
    session_start();
}

/* ===== LIBRARIES USED THROUGHOUT THE THEME */
/* CDNJS - v4.1.1 - BOOTSTRAP */
define('CSS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css');
define('JS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js');
