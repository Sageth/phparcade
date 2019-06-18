<?php

require_once __DIR__ . '/cfg.php';

$router = new Phroute\Phroute\RouteCollector();

/* Game Routing
 catch http://phparcade.dev/game/2741/Sample.html */
$router->any(['/game/{id:i}/{gameName:.*}', 'game'], function ($id, $gameName) use (&$foundMatch) {
    $game = PHPArcade\Games::getGame($id);
    $cleanGameName = PHPArcade\Core::getCleanURL($game['name']) . '.html';
    if ($cleanGameName != urldecode($gameName)) {
        header('Location: /game/'.$id.'/'.urlencode($cleanGameName));
        return false;
    } else {
        $_GET['params'] = 'game/'.$id.'/'. PHPArcade\Core::getCleanURL($game['name']);
    }
});

/* User Profile Routing
   catch http://phparcade.dev/profile/view/1/Username.html */
$router->any(['/profile/view/{id:i}/{userProfile:.*}', 'user'], function ($id, $userProfile) use (&$foundMatch) {
    $user = PHPArcade\Users::getUserbyID($id);
    $cleanUserName = PHPArcade\Core::getCleanURL($user['username']) . '.html';
    if ($cleanUserName != urldecode($userProfile))
    {
        header('Location: /profile/view/' . $id . '/' . urlencode($cleanUserName));
        return false;
    } else
    {
        $_GET['params'] = 'profile/view/' . $id . '/' . PHPArcade\Core::getCleanURL($user['username']);
    }
});

/* catch all routes not caught earlier */
$router->any('{route:.*}', function () {
});

$response = (new Phroute\Phroute\Dispatcher($router->getData()))->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

require_once(INST_DIR . 'includes/first.php');
	/** @noinspection PhpIncludeInspection */
	require_once(SITE_THEME_PATH);
