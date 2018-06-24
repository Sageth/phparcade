<?php
PHPArcade\Core::stopDirectAccess();
if (!isset($_SESSION)) {
    session_start();
}

/* ===== LIBRARIES USED THROUGHOUT THE THEME */
/* CDNJS - v3.3.7 - BOOTSTRAP */
define('CSS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css');
define('CSS_BOOTSTRAP_SRI', 'sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=');

define('JS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js');
define('JS_BOOTSTRAP_SRI', 'sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=');
