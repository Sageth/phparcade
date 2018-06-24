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
include_once __DIR__ . '/scoresys.php';
require_once __DIR__ . '/themeconfig.php';
?>

<!DOCTYPE html>
<!--suppress JSIgnoredPromiseFromCall -->
<html lang="en" xmlns="https://www.w3.org/1999/xhtml" prefix="og:http://ogp.me/ns#">
    <head>
        <?php if ('on' === $dbconfig['gtm_enabled']) { ?>
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo $dbconfig['gtm_id'];?>');
            </script>
            <!-- End Google Tag Manager -->
        <?php } ?>
        <?php if (['google_analytics_pubid'] !== '') { ?>
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({
                    google_ad_client: "<?php echo $dbconfig['google_analytics_pubid'];?>",
                    enable_page_level_ads: true
                });
            </script>
        <?php } ?>
        <meta charset="<?php echo CHARSET; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <title><?php echo $metadata['metapagetitle']; ?></title>
        <link rel="manifest" href="<?php echo SITE_URL;?>manifest.json" />
        <link rel="preconnect" href="https://cdnjs.cloudflare.com" />

        <!-- Run this first so you get your local CSS loaded before external JS -->
        <?php switch (true) {
            case PHPArcade\Core::is('home'): ?>
                <link rel="stylesheet" href="<?php echo SITE_THEME_URL; ?>assets/css/home.style.min.css" /><?php
                break;
            case PHPArcade\Core::is('game'):
                /** @noinspection PhpUndefinedVariableInspection */
                $game = PHPArcade\Games::getGame($params[1]); ?>
                <meta property="og:type" content="video.movie"/>
                <meta property="og:title" content="<?php echo $game['name'];?>"/>
                <meta property="og:image" content="<?php echo $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>"/>
                <meta property="og:url" content="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"/>
                <meta property="og:description" content="<?php echo strip_tags($game['desc']); ?>"/>
                <meta property="fb:app_id" content="<?php echo $dbconfig['facebook_appid']; ?>"/><?php
                break;
            case PHPArcade\Core::is('register'): ?>
                <link rel="stylesheet" href="<?php echo SITE_THEME_URL;?>assets/css/login.style.min.css" />
                <script src="<?php echo JS_GOOGLE_RECAPTCHA; ?>" defer></script><?php
                break;
            default:
        } ?>

        <!-- Load everything else -->
        <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP; ?>" integrity="<?php echo CSS_BOOTSTRAP_SRI;?>" crossorigin="anonymous" />
        <link rel="stylesheet" href="<?php echo CSS_FONTAWESOME; ?>" integrity="<?php echo CSS_FONTAWESOME_SRI;?>" crossorigin="anonymous"/>
        <link rel="canonical" href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"/>
        <link rel="alternate" href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" hreflang="en"/>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_URL; ?>favicon.ico" title="FavIcon"/>
        <meta name="description" content="<?php echo $metadata['metapagedesc']; ?>"/>
        <meta name="keywords" content="<?php echo $metadata['metapagekeywords']; ?>"/>
        <meta name="language" content="English"/>
        <meta name="no-email-collection" content="https://www.unspam.com/noemailcollection"/>
        <meta name="robots" content="noarchive"/>
        <?php if (!empty($dbconfig['mixpanel_id'])) {
            include (INST_DIR . 'includes/js/MixPanel/mixpanel.php');
        } ?>
    </head>
    <body>
        <?php if ('on' === $dbconfig['gtm_enabled']) {
            include_once INST_DIR . 'includes/js/Google/googletagmanager.php';
        }
        include_once __DIR__ . '/navbar.php';
        if (true == PHPArcade\Core::is('home')) {
            include_once __DIR__ . '/carousel.php';
        } ?>
        <!--Content Section -->
        <div class="container">
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
        <?php require_once __DIR__ . '/footer.php'; ?>
        <script src="<?php echo JS_JQUERY; ?>" integrity="<?php echo JS_JQUERY_SRI;?>" crossorigin="anonymous" async></script>
        <script src="<?php echo JS_BOOTSTRAP; ?>" integrity="<?php echo JS_BOOTSTRAP_SRI;?>" crossorigin="anonymous" async></script>
        <?php if (true == PHPArcade\Core::is('game')) { ?>
            <script src="<?php echo JS_SWFOBJECT;?>" integrity="<?php echo JS_SWFOBJECT_SRI;?>" crossorigin="anonymous" async></script><?php
        } ?>
        <script type="application/ld+json" async>
        {
            "@context":"http://schema.org",
            "@type":"Organization",
            "name":"<?php echo $dbconfig['sitetitle'];?>",
            "url":"<?php echo SITE_URL;?>",
            "sameAs": [
                "<?php echo $dbconfig['facebook_pageurl'];?>"
            ]
        }
        </script>
        <script type="application/ld+json" async>
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
        <script type="application/ld+json" async>
            <?php if (!empty($dbconfig['mixpanel_id'])) {
                if (PHPArcade\Users::isUserLoggedIn() === true) { ?>
                    mixpanel.register({
                        "$admin": "<?php echo $user['admin'];?>",
                        "$birthdate": "<?php echo $user['birth_date'];?>",
                        "$created": "<?php echo date('Y-m-d H:i:s', $user['regtime']);?>",
                        "$facebook_id": "<?php echo $user['facebook'];?>",
                        "$github_id": "<?php echo $user['github'];?>",
                        "$id": "<?php echo $user['id'];?>",
                        "$email": "<?php echo $user['email'];?>",
                        "$last_login": "<?php echo date('Y-m-d H:i:s', $user['last_login']);?>",
                        "$total_games_played": "<?php echo $user['totalgames'];?>",
                        "$username": "<?php echo $user['name'];?>"
                    });
                    mixpanel.identify('<?php echo $user['id'];?>');
                    mixpanel.people.set({
                        "$admin": "<?php echo $user['admin'];?>",
                        "$birthdate": "<?php echo $user['birth_date'];?>",
                        "$created": "<?php echo date('Y-m-d H:i:s', $user['regtime']);?>",
                        "$facebook_id": "<?php echo $user['facebook'];?>",
                        "$github_id": "<?php echo $user['github'];?>",
                        "$id": "<?php echo $user['id'];?>",
                        "$email": "<?php echo $user['email'];?>",
                        "$last_login": "<?php echo date('Y-m-d H:i:s', $user['last_login']);?>",
                        "$total_games_played": "<?php echo $user['totalgames'];?>",
                        "$username": "<?php echo $user['name'];?>"
                    })<?php
                } else { ?>
                    mixpanel.register("<?php echo session_id();?>");<?php
                }
            } ?>
        </script>

        <!-- LazyLoader -->
        <script>
            window.lazyLoadOptions = {
                /* Load options here. We just use the defaults.
                   See more: https://www.andreaverlicchi.eu/lazyload/#recipes */
            };
        </script>
        <script async src="<?php echo JS_LAZYLOAD; ?>" integrity="<?php echo JS_LAZYLOAD_SRI;?>" crossorigin="anonymous"></script>
        <!-- End LazyLoader -->
        <?php
        $inicfg = PHPArcade\Core::getINIConfig();
        if ($inicfg['webhook']['highscoreURI'] != '') { ?>
            <script src="https://cdn.jsdelivr.net/npm/@widgetbot/crate@3" async>
                if (window.innerWidth > 768) {
                    new Crate({
                        server: '<?php echo $inicfg['webhook']['hs_server'];?>',
                        channel: '<?php echo $inicfg['webhook']['hs_channel'];?>',
                        location: ['bottom', 'right'],
                        notifications: true,
                        indicator: true,
                        username: '<?php echo $user['name'];?>'
                    })
                }
            </script><?php
        }
        ?>
    </body>
</html>