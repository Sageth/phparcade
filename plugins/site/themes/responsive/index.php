<?php
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION) {
    $user = $_SESSION;
}
$dbconfig = Core::getDBConfig();
$metadata = Core::getPageMetaData();
include_once __DIR__ . '/themeconfig.php';
include_once __DIR__ . '/scoresys.php'; ?>
<!DOCTYPE html>
<html lang="en" xmlns="https://www.w3.org/1999/xhtml" xmlns:fb="https://ogp.me/ns/fb#">
    <head>
        <meta charset="<?php echo CHARSET; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <title><?php echo $metadata['metapagetitle']; ?></title>
        <link rel="alternate"
              type="application/rss+xml"
              href="<?php echo SITE_URL; ?>"
              title="<?php echo $dbconfig['sitetitle']; ?>"/>
        <link rel="canonical" href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"/>
        <link rel="alternate" href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" hreflang="en"/>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_URL; ?>favicon.ico" title="FavIcon"/>
        <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP; ?>"/>
        <!-- This section does lazy loading of the CSS as described here: https://github.com/filamentgroup/loadCSS/ -->
        <link rel="preload" href="<?php echo CSS_BOOTSTRAP_THEME; ?>" as="style" onload="this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP_THEME; ?>"/>
        </noscript>
        <link rel="preload" href="<?php echo CSS_FONTAWESOME; ?>" as="style" onload="this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="<?php echo CSS_FONTAWESOME; ?>"/>
        </noscript>
        <!-- End lazy loading -->
        <meta name="description" content="<?php echo $metadata['metapagedesc']; ?>"/>
        <meta name="keywords" content="<?php echo $metadata['metapagekeywords']; ?>"/>
        <meta name="language" content="English"/>
        <meta name="no-email-collection" content="https://www.unspam.com/noemailcollection"/>
        <meta name="robots" content="index,follow"/><?php
        switch (true) {
            case is('home'): ?>
                <link rel="stylesheet" href="<?php echo SITE_THEME_DIR; ?>css/style.min.css" /><?php
                break;
            case is('game'):
                /** @noinspection PhpUndefinedVariableInspection */
                $game = Games::getGame($params[1]); ?>
                <meta property="og:title" content="<?php echo $metadata['metapagetitle']; ?>"/>
                <meta property="og:type" content="video.movie"/>
                <meta property="og:image" content="<?php echo $dbconfig['imgurl'] . $game['nameid']; ?>.png"/>
                <meta property="og:url" content="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"/>
                <meta property="og:description" content="<?php echo $game['desc']; ?>"/>
                <meta property="fb:app_id" content="<?php echo $dbconfig['facebook_appid']; ?>"/><?php
                break;
            case is('register'): ?>
                <script src="<?php echo JS_GOOGLE_RECAPTCHA; ?>"></script><?php
            default:
        }
        if ($dbconfig['spotim_on'] === 'on') {
            include_once INST_DIR . 'includes/js/Spotim/spotim.php';
        } ?>
    </head>
    <body><?php
        if ($dbconfig['ga_enabled'] === 'on') {
            include_once INST_DIR . 'includes/js/Google/googletagmanager.php';
        }
        include_once __DIR__ . '/navbar.php';
        if (true == is('home')) {
            include_once __DIR__ . '/carousel.php';
        } /** @noinspection MissingOrEmptyGroupStatementInspection */ else {
            /* Do nothing */
        } ?>
        <!--Content Section -->
        <div class="container">
            <div class="row"><?php
                switch (true) {
                    case is('home'):
                        include_once __DIR__ . '/home.php';
                        break;
                    case is('game'):
                        include_once __DIR__ . '/game.php';
                        break;
                    case is('register'):
                        include_once __DIR__ . '/register.php';
                        break;
                    case is('login'):
                        include_once __DIR__ . '/login.php';
                        break;
                    case is('profile'):
                        include_once __DIR__ . '/profile.php';
                        break;
                    case is('category'):
                        include_once __DIR__ . '/category.php';
                        break;
                    case is('page'):
                        include_once __DIR__ . '/page.php';
                        break;
                    case is('search'):
                        include_once __DIR__ . '/search.php';
                        break;
                    default:
                        include_once __DIR__ . '/error.php';
                } ?>
            </div>
        </div>
        <?php require_once __DIR__ . '/footer.php'; ?>
        <script src="<?php echo JS_JQUERY; ?>"></script>
        <script src="<?php echo JS_BOOTSTRAP; ?>"></script><?php
        if (true == is('game')) { ?>
            <!--suppress JSUnresolvedLibraryURL -->
            <script type="text/javascript" src="<?php echo JS_SWFOBJECT; ?>"></script><?php
        } /** @noinspection MissingOrEmptyGroupStatementInspection */ else {
            /* Do nothing */
        } ?>
    </body>
</html>