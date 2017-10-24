<?php
require_once __DIR__ . '/cfg.php';

$builder = new DI\ContainerBuilder();
$container = $builder->build();

$resolver = new \PhpArcade\HandlerResolver\RouterResolver($container);

$router = new Phroute\Phroute\RouteCollector();

// catch http://phparcade.dev/game/2741/Sample.html
$router->any(['/game/{id:i}/{passedName:.*}', 'game'], ['PhpArcade\Controller\Game', 'indexAction']);
$container->make('PhpArcade\Controller\Game', ['container' => $container]);

//catch all routes not caught earlier
$router->any('{route:.*}', function(){
    return false;
});

$hasResponse = (new Phroute\Phroute\Dispatcher($router->getData(), $resolver))->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

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
if(!$hasResponse) {
    include SITE_THEME_PATH;
}