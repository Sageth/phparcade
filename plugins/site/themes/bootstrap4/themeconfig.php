<?php
PHPArcade\Core::stopDirectAccess();
if (!isset($_SESSION)) {
    session_start();
}

/* ===== LIBRARIES USED THROUGHOUT THE THEME */
/* CDNJS - v4.1.1 - BOOTSTRAP */
define('CSS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css');
define('CSS_BOOTSTRAP_SRI', 'sha256-eSi1q2PG6J7g7ib17yAaWMcrr5GrtohYChqibrV7PBE=');

define('JS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js');
define('JS_BOOTSTRAP_SRI', 'sha256-VsEqElsCHSGmnmHXGQzvoWjWwoznFSZc6hs7ARLRacQ=');
