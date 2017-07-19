<?php
if (!isset($_SESSION)) {session_start();}
if ($_SESSION) {$user = $_SESSION;}
$dbconfig = Core::getDBConfig();
$metadata = Core::getPageMetaData();
include_once __DIR__ . '/themeconfig.php';
include_once __DIR__ . '/scoresys.php'; ?>

<!DOCTYPE html>
    <?php switch (true) {
        case is('game'):
            echo '<html itemscope itemtype="https://schema.org/Game" lang="en" xmlns="https://www.w3.org/1999/xhtml" xmlns:fb="https://ogp.me/ns/fb#">';
            break;
        default:
            echo '<html lang="en" xmlns="https://www.w3.org/1999/xhtml" xmlns:fb="https://ogp.me/ns/fb#">';
    } ?>
    <head>
        <meta charset="<?php echo CHARSET; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <title><?php echo $metadata['metapagetitle']; ?></title>
        <link rel="alternate" type="application/rss+xml" href="<?php echo SITE_URL; ?>" title="<?php echo $dbconfig['sitetitle']; ?>"/>
        <link rel="canonical" href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"/>
        <link rel="alternate" href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" hreflang="en"/>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_URL; ?>favicon.ico" title="FavIcon"/>
        <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP; ?>"/>
        <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP_THEME; ?>"/>
        <link rel="stylesheet" href="<?php echo CSS_FONTAWESOME; ?>"/>
        <link itemprop="additionalType" href="http://schema.org/WebPage"/>
        <meta name="description" content="<?php echo $metadata['metapagedesc']; ?>"/>
        <meta name="keywords" content="<?php echo $metadata['metapagekeywords']; ?>"/>
        <meta name="language" content="English"/>
        <meta name="no-email-collection" content="https://www.unspam.com/noemailcollection"/>
        <meta name="robots" content="noarchive"/>
        <?php switch (true) {
            case is('home'): ?>
                <link rel="stylesheet" href="<?php echo SITE_THEME_DIR; ?>css/style.min.css" /><?php
                break;
            case is('game'):
                /** @noinspection PhpUndefinedVariableInspection */
                $game = Games::getGame($params[1]); ?>
                <meta itemprop="alternativeHeadline" property="og:title" content="<?php echo $metadata['metapagetitle']; ?>"/>
                <meta property="og:type" content="video.movie"/>
                <meta itemprop="keywords" property="og:keywords" content="<?php echo $game['keywords'];?>" />
                <meta itemprop="thumbnailUrl" property="og:image" content="<?php echo $dbconfig['imgurl'] . $game['nameid']; ?>.png"/>
                <meta itemprop="image" property="og:image" content="<?php echo $dbconfig['imgurl'] . $game['nameid']; ?>.png"/>
                <meta itemprop="url" property="og:url" content="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"/>
                <meta itemprop="description" property="og:description" content="<?php echo $game['desc']; ?>"/>
                <meta itemprop="identifier" property="fb:app_id" content="<?php echo $dbconfig['facebook_appid']; ?>"/><?php
                break;
            case is('register'): ?>
                <script src="<?php echo JS_GOOGLE_RECAPTCHA; ?>" defer></script><?php
            default:
        } ?>
    </head>
    <body>
        <?php if ($dbconfig['ga_enabled'] === 'on') {
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
            <div class="row">
                <?php switch (true) {
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
        <script src="<?php echo JS_BOOTSTRAP; ?>"></script>
        <?php if (true == is('game')) { ?>
            <!--suppress JSUnresolvedLibraryURL -->
            <script type="text/javascript" src="<?php echo JS_SWFOBJECT; ?>"></script><?php
        } /** @noinspection MissingOrEmptyGroupStatementInspection */ else {
            /* Do nothing */
        } ?>
    </body>
</html>