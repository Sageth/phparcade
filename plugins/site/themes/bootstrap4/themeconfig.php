<?php
PHPArcade\Core::stopDirectAccess();
if (!isset($_SESSION)) {
    session_start();
}

/* ===== LIBRARIES USED THROUGHOUT THE THEME */
define('CSS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css');
define('CSS_BOOTSTRAP_SRI', 'sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=');

define('JS_BOOTSTRAP', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js');
define('JS_BOOTSTRAP_SRI', 'sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=');
