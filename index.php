<?php
require_once __DIR__ . '/cfg.php';

/* Enable debug logging in non-prod */
$inicfg = Core::getINIConfig();
if ($inicfg['environment']['state'] === "dev"){
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

require_once INST_DIR . 'includes/first.php';

Core::doEvent('pluginsloaded');
Core::doEvent('theme_display');

/** @noinspection PhpUndefinedVariableInspection */
/** @noinspection PhpIncludeInspection */
include $config['themeinc'];
