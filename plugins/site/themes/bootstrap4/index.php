<?php
/* Fixes errors on score submission because the autoloader isn't found */
require_once $_SERVER['DOCUMENT_ROOT'] . '/cfg.php';

if (!isset($_SESSION)) {
    session_start();
}
$user = empty($_SESSION) ? array("name" => "-") : $_SESSION;

$dbconfig = PHPArcade\Core::getDBConfig();
$metadata = PHPArcade\Core::getPageMetaData();

/* Registers the score system. Must be called after session start */
PHPArcade\Scores::registerScoreSystem();

require_once __DIR__ . '/themeconfig.php'; ?>
<!DOCTYPE html>
<!--suppress JSIgnoredPromiseFromCall, HtmlUnknownTag -->
<html lang="en" prefix="og:http://ogp.me/ns#" xmlns="https://www.w3.org/1999/xhtml">
    <head>
        <?php if ('on' === $dbconfig['gtm_enabled']) { ?>
            <!-- Google Tag Manager -->
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $dbconfig['gtm_id'];?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?php echo $dbconfig['gtm_id'];?>');
            </script>
            <!-- End Google Tag Manager -->
            <?php
        } ?>
        <meta charset="<?php echo CHARSET; ?>">
        <meta content="width=device-width, initial-scale=1.0, user-scalable=yes" name="viewport">
        <title><?php echo $metadata['metapagetitle']; ?></title>
        <link href="<?php echo SITE_URL;?>manifest.json" rel="manifest"/>
        <link href="https://cdnjs.cloudflare.com" rel="preconnect"/>

        <!-- Run this first so you get your local CSS loaded before external JS -->
        <?php
        switch (true) {
            case PHPArcade\Core::is('home'): ?>
                <link href="<?php echo SITE_THEME_URL; ?>assets/css/home.style.min.css" rel="stylesheet"/><?php
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
                <link href="<?php echo SITE_THEME_URL;?>assets/css/login.style.min.css" rel="stylesheet"/>
                <script async src="<?php echo JS_GOOGLE_RECAPTCHA; ?>"></script><?php
                break;
            default:
        } ?>

        <!-- Load everything else -->
        <link crossorigin="anonymous" href="<?php echo CSS_BOOTSTRAP; ?>" integrity="<?php echo CSS_BOOTSTRAP_SRI;?>"
              rel="stylesheet"/>
        <link crossorigin="anonymous" href="<?php echo CSS_FONTAWESOME; ?>" integrity="<?php echo CSS_FONTAWESOME_SRI;?>"
              rel="stylesheet"/>
        <link href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" rel="canonical"/>
        <link href="<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>" hreflang="en" rel="alternate"/>
        <link href="<?php echo SITE_URL; ?>favicon.ico" rel="shortcut icon" title="FavIcon" type="image/x-icon"/>
        <meta content="<?php echo $metadata['metapagedesc']; ?>" name="description"/>
        <meta content="<?php echo $metadata['metapagekeywords']; ?>" name="keywords"/>
        <meta content="English" name="language"/>
        <meta content="https://www.unspam.com/noemailcollection" name="no-email-collection"/>
        <meta content="noarchive" name="robots"/>
        <?php if (!empty($dbconfig['mixpanel_id'])) {
            include(INST_DIR . 'includes/js/MixPanel/mixpanel.php');
        } ?>
    </head>
    <body>
        <?php include_once __DIR__ . '/navbar.php';
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
        <script crossorigin="anonymous" defer integrity="<?php echo JS_JQUERY_SRI;?>" src="<?php echo JS_JQUERY; ?>"></script>
        <script crossorigin="anonymous" defer integrity="<?php echo JS_BOOTSTRAP_SRI;?>"
                src="<?php echo JS_BOOTSTRAP; ?>"></script>
        <?php if (true == PHPArcade\Core::is('game')) {
                ?>
            <script async crossorigin="anonymous" integrity="<?php echo JS_SWFOBJECT_SRI; ?>"
                    src="<?php echo JS_SWFOBJECT; ?>"></script><?php
            } ?>
        <script async type="application/ld+json">
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
        <script async type="application/ld+json">
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
        <!--suppress JSUnresolvedFunction -->
        <script async>
            <?php if (!empty($dbconfig['mixpanel_id'])) {
                if (PHPArcade\Users::isUserLoggedIn() === true) {
                    ?>
                    mixpanel.register({
                        "$admin": "<?php echo $user['admin']; ?>",
                        "$birthdate": "<?php echo date('Y-m-d', $user['birth_date']); ?>",
                        "$created": "<?php echo date('Y-m-d H:i:s', $user['regtime']); ?>",
                        "$facebook_id": "<?php echo $user['facebook_id']; ?>",
                        "$github_id": "<?php echo $user['github_id']; ?>",
                        "$id": "<?php echo $user['id']; ?>",
                        "$email": "<?php echo $user['email']; ?>",
                        "$last_login": "<?php echo date('Y-m-d H:i:s', $user['last_login']); ?>",
                        "$total_games_played": "<?php echo $user['totalgames']; ?>",
                        "$username": "<?php echo $user['username']; ?>",
                    });
                    mixpanel.identify('<?php echo $user['id']; ?>');
                    mixpanel.people.set({
                        "$admin": "<?php echo $user['admin']; ?>",
                        "$birthdate": "<?php echo date('Y-m-d', $user['birth_date']); ?>",
                        "$created": "<?php echo date('Y-m-d H:i:s', $user['regtime']); ?>",
                        "$facebook_id": "<?php echo $user['facebook_id']; ?>",
                        "$github_id": "<?php echo $user['github_id']; ?>",
                        "$id": "<?php echo $user['id']; ?>",
                        "$email": "<?php echo $user['email']; ?>",
                        "$last_login": "<?php echo date('Y-m-d H:i:s', $user['last_login']); ?>",
                        "$total_games_played": "<?php echo $user['totalgames']; ?>",
                        "$username": "<?php echo $user['username']; ?>",
                    });
                  <?php
                } else {
                    ?>
                    mixpanel.register("<?php echo session_id(); ?>");
                    <?php
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
        <script async crossorigin="anonymous" integrity="<?php echo JS_LAZYLOAD_SRI;?>" src="<?php echo JS_LAZYLOAD; ?>"></script>
        <!-- End LazyLoader -->
    </body>
</html>