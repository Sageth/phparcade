<?php
/* Fixes errors on score submission because the autoloader isn't found */
require_once $_SERVER['DOCUMENT_ROOT'] . '/cfg.php';

if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION) {
    $user = $_SESSION;
}
$dbconfig = PHPArcade\Core::getDBConfig();
$metadata = PHPArcade\Core::getPageMetaData();

/* Registers the score system. Must be called after session start */
PHPArcade\Scores::registerScoreSystem();

require_once __DIR__ . '/themeconfig.php';
?>

<!DOCTYPE html>
<!--suppress JSIgnoredPromiseFromCall, HtmlUnknownTag -->
<html lang="en" prefix="og:http://ogp.me/ns#" xmlns="https://www.w3.org/1999/xhtml">
    <head>
        <meta charset="<?php echo CHARSET; ?>">
        <meta content="width=device-width, initial-scale=1.0, user-scalable=yes" name="viewport">
        <title><?php echo $metadata['metapagetitle']; ?></title>
        <link href="<?php echo SITE_URL;?>manifest.json" rel="manifest"/>
        <link href="https://cdnjs.cloudflare.com" rel="preconnect"/>

        <!-- Run this first so you get your local CSS loaded before external JS -->
        <?php switch (true) {
            case PHPArcade\Core::is('home'): ?>
                <link rel="stylesheet" href="<?php echo SITE_THEME_URL; ?>css/home.style.min.css" /><?php
                break;
            case PHPArcade\Core::is('game'):
                /** @noinspection PhpUndefinedVariableInspection */
                $game = PHPArcade\Games::getGame($params[1]); ?>
                <meta content="video.movie" property="og:type"/>
                <meta content="<?php echo $game['name'];?>" property="og:title"/>
                <meta content="<?php echo $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>" property="og:image"/>
                <meta content="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" property="og:url"/>
                <meta content="<?php echo strip_tags($game['desc']); ?>" property="og:description"/>
                <meta content="<?php echo $dbconfig['facebook_appid']; ?>" property="fb:app_id"/><?php
                break;
            case PHPArcade\Core::is('register'): ?>
                <script async src="<?php echo JS_GOOGLE_RECAPTCHA; ?>"></script><?php
            // no break
            default:
        } ?>

        <!-- Load everything else -->
        <link crossorigin="anonymous" href="<?php echo CSS_BOOTSTRAP; ?>" integrity="<?php echo CSS_BOOTSTRAP_SRI;?>"
              rel="stylesheet"/>
        <link href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" rel="canonical"/>
        <link href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" hreflang="en" rel="alternate"/>
        <link href="<?php echo SITE_URL; ?>favicon.ico" rel="shortcut icon" title="FavIcon" type="image/x-icon"/>
        <meta content="<?php echo $metadata['metapagedesc']; ?>" name="description"/>
        <meta content="<?php echo $metadata['metapagekeywords']; ?>" name="keywords"/>
        <meta content="English" name="language"/>
        <meta content="https://www.unspam.com/noemailcollection" name="no-email-collection"/>
        <meta content="noarchive" name="robots"/>
    </head>
    <body>
        <?php
        include_once __DIR__ . '/navbar.php';
        if (true == PHPArcade\Core::is('home')) {
            include_once __DIR__ . '/carousel.php';
        } ?>
        <!--Content Section -->
        <div class="container">
            <div class="row">
                <?php switch (true) {
                    case PHPArcade\Core::is('home'):
                        include_once __DIR__ . '/home.php';
                        break;
                    case PHPArcade\Core::is('game'):
                        include_once __DIR__ . '/game.php';
                        break;
                    case PHPArcade\Core::is('register'):
                        include_once __DIR__ . '/register.php';
                        break;
                    case PHPArcade\Core::is('login'):
                        include_once __DIR__ . '/login.php';
                        break;
                    case PHPArcade\Core::is('profile'):
                        include_once __DIR__ . '/profile.php';
                        break;
                    case PHPArcade\Core::is('category'):
                        include_once __DIR__ . '/category.php';
                        break;
                    case PHPArcade\Core::is('page'):
                        include_once __DIR__ . '/page.php';
                        break;
                    case PHPArcade\Core::is('search'):
                        include_once __DIR__ . '/search.php';
                        break;
                    default:
                        include_once __DIR__ . '/error.php';
                } ?>
            </div>
        </div>
        <?php require_once __DIR__ . '/footer.php'; ?>
        <script crossorigin="anonymous" defer integrity="<?php echo JS_JQUERY_SRI;?>" src="<?php echo JS_JQUERY; ?>"></script>
        <script crossorigin="anonymous" defer integrity="<?php echo JS_BOOTSTRAP_SRI;?>"
                src="<?php echo JS_BOOTSTRAP; ?>"></script>
        <?php if (true == PHPArcade\Core::is('game')) { ?>
            <script async crossorigin="anonymous" integrity="<?php echo JS_SWFOBJECT_SRI;?>"
                    src="<?php echo JS_SWFOBJECT;?>"></script><?php
        }

        include_once INST_DIR . 'includes/js/Schema/siteschema.php';
	    include_once INST_DIR . 'includes/js/Schema/pageschema.php';  ?>

        <!-- LazyLoader BEGIN -->
        <script crossorigin="anonymous" integrity="<?php echo JS_LAZYLOAD_SRI;?>" src="<?php echo JS_LAZYLOAD; ?>"></script>
        <script>
            (function() {
                let myLazyLoad = new LazyLoad({
                    load_delay: 300 //adjust according to use case
                });
            })();
        </script>
        <!-- LazyLoader END-->

        <!-- Font Awesome BEGIN-->
        <script async src="https://kit.fontawesome.com/<?php echo FONT_AWESOME_KIT;?>.js" crossorigin="anonymous"></script>
        <!-- Font Awesome END -->
    </body>
</html>