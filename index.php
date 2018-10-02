<?php

require_once __DIR__ . '/cfg.php';

$router = new Phroute\Phroute\RouteCollector();

// Game Routing
// catch http://phparcade.dev/game/2741/Sample.html
$router->any(['/game/{id:i}/{passedName:.*}', 'game'], function ($id, $passedName) use (&$foundMatch) {
    $game = PHPArcade\Games::getGame($id);
    $actualNameWithHtml = PHPArcade\Core::getCleanURL($game['name']) . '.html';
    if ($actualNameWithHtml != urldecode($passedName)) {
        header('Location: /game/'.$id.'/'.urlencode($actualNameWithHtml));
        return false;
    } else {
        $_GET['params'] = 'game/'.$id.'/'. PHPArcade\Core::getCleanURL($game['name']);
    }
});

//catch all routes not caught earlier
$router->any('{route:.*}', function () {
});

$response = (new Phroute\Phroute\Dispatcher($router->getData()))->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

/* Enable debug logging in non-prod */
$inicfg = PHPArcade\Core::getINIConfig();
if ($inicfg['environment']['state'] === "dev") {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

require_once INST_DIR . 'includes/first.php';

/** @noinspection PhpUndefinedVariableInspection */
/** @noinspection PhpIncludeInspection */
include SITE_THEME_PATH;
