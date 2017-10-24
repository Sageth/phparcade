<?php
/* Fixes errors on score submission because the autoloader isn't found */
require_once $_SERVER['DOCUMENT_ROOT'] . '/cfg.php';

if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION) {
    $user = $_SESSION;
}
$dbconfig = Core::getInstance()->getDBConfig();
$metadata = Core::getPageMetaData();
include_once __DIR__ . '/scoresys.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="https://www.w3.org/1999/xhtml" prefix="og:http://ogp.me/ns#">
    <head>
        <meta charset="<?php echo CHARSET; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <title><?php echo $metadata['metapagetitle']; ?></title>
        <!-- Preconnections -->
        <link rel="preconnect" href="https://cdnjs.cloudflare.com">
        <!-- End Preconnections -->

        <!-- Run this first so you get your local CSS loaded before external JS -->
        <?php switch ($this->e("page")) {
            case 'home': ?>
                <link rel="stylesheet" href="<?php echo SITE_THEME_URL; ?>css/home.style.min.css" /><?php
                break;
            case 'game':
                /** @noinspection PhpUndefinedVariableInspection */
                $game = Games::getGame($params[1]); ?>
                <meta property="og:type" content="video.movie"/>
                <meta property="og:title" content="<?php echo $game['name'];?>"/>
                <meta property="og:image" content="<?php echo $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>"/>
                <meta property="og:url" content="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"/>
                <meta property="og:description" content="<?php echo strip_tags($game['desc']); ?>"/>
                <meta property="fb:app_id" content="<?php echo $dbconfig['facebook_appid']; ?>"/><?php
                break;
            case 'register': ?>
                <script src="<?php echo JS_GOOGLE_RECAPTCHA; ?>" defer></script><?php
            // no break
            default:
        } ?>

        <!-- Load everything else -->
        <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP; ?>"/>
        <link rel="stylesheet" href="<?php echo CSS_FONTAWESOME; ?>"/>
        <link rel="canonical" href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"/>
        <link rel="alternate" href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" hreflang="en"/>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_URL; ?>favicon.ico" title="FavIcon"/>
        <meta name="description" content="<?php echo $metadata['metapagedesc']; ?>"/>
        <meta name="keywords" content="<?php echo $metadata['metapagekeywords']; ?>"/>
        <meta name="language" content="English"/>
        <meta name="no-email-collection" content="https://www.unspam.com/noemailcollection"/>
        <meta name="robots" content="noarchive"/>
    </head>
    <body>
        <?php if ('on' === $dbconfig['ga_enabled']) {
            include_once INST_DIR . 'includes/js/Google/googletagmanager.php';
        }
        include_once __DIR__ . '/navbar.php';
        if (true == is('home')) {
            include_once __DIR__ . '/carousel.php';
        } ?>
        <!--Content Section -->
        <div class="container">
            <div class="row">
                <?=$this->section('content')?>
            </div>
        </div>
        <?php require_once __DIR__ . '/footer.php'; ?>
        <!--suppress XmlDefaultAttributeValue -->
        <script src="<?php echo JS_JQUERY; ?>" defer></script>
        <!--suppress XmlDefaultAttributeValue -->
        <script src="<?php echo JS_BOOTSTRAP; ?>" defer></script>
        <?php if (true == is('game')) {
            ?>
            <!--suppress JSUnresolvedLibraryURL, XmlDefaultAttributeValue -->
            <script type="text/javascript" src="<?php echo JS_SWFOBJECT; ?>"
                    crossorigin="anonymous" defer></script><?php
        } ?>
        <script type="application/ld+json" defer>
        {
            "@context":"http://schema.org",
            "@type":"Organization",
            "name":"<?php echo $dbconfig['sitetitle'];?>",
            "url":"<?php echo SITE_URL;?>",
            "sameAs": [
                "<?php echo URL_TWITTER . $dbconfig['twitter_username'];?>",
                "<?php echo $dbconfig['facebook_pageurl'];?>"
            ]
        }
        </script>

        <script type="application/ld+json" defer>
        {
            "@context":"http://schema.org",
            "@type":"WebSite",
            "name":"<?php echo SITE_META_TITLE;?>",
            "alternateName": "Play fun flash games, html5 games, and mobile games for free.",
            "url":"<?php echo SITE_URL;?>",
            "exampleOfWork":"<?php echo URL_GITHUB_PHPARCADE;?>",
            "sameAs":"<?php echo URL_GITHUB_PHPARCADE;?>",
            "isAccessibleForFree":"true",
            "keywords":"<?php echo SITE_META_KEYWORDS;?>",
            "description":"<?php echo strip_tags(SITE_META_DESCRIPTION);?>",
            "license":"<?php echo SITE_URL;?>LICENSE.md",
            "workExample":"https://www.phparcade.com",
            "potentialAction" : {
                "@type" : "SearchAction",
                "target" : "<?php echo SITE_URL;?>?q={search_term}",
                "query-input" : "required name=search_term"
            }
        }
        </script>
    </body>
</html>