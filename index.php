<?php
require_once __DIR__ . '/cfg.php';

$router = new Phroute\Phroute\RouteCollector();

if(!isset($_COOKIE['SameSite'])) {
    setcookie("SameSite", 'Strict');
}

// Game Routing
// catch http://phparcade.dev/game/2741/Sample.html
$router->any(['/game/{id:i}/{passedName:.*}', 'game'], function ($id, $passedName) use (&$foundMatch) {
    $game = Games::getGame($id);
    $actualNameWithHtml = Core::getCleanURL($game['name']) . '.html';
    if ($actualNameWithHtml != urldecode($passedName)) {
        header('Location: /game/'.$id.'/'.urlencode($actualNameWithHtml));
        return false;
    } else {
        $_GET['params'] = 'game/'.$id.'/'. Core::getCleanURL($game['name']);
    }
});

//catch all routes not caught earlier
$router->any('{route:.*}', function () {
});

$response = (new Phroute\Phroute\Dispatcher($router->getData()))->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

/* Enable debug logging in non-prod */
$inicfg = Core::getInstance()->getINIConfig();
if ($inicfg['environment']['state'] === "dev") {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

require_once INST_DIR . 'includes/first.php';

Core::doEvent('pluginsloaded');
Core::doEvent('theme_display');

/** @noinspection PhpUndefinedVariableInspection */
/** @noinspection PhpIncludeInspection */
include SITE_THEME_PATH;
