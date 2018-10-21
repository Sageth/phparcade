<?php
PHPArcade\Core::stopDirectAccess();
if (!PHPArcade\Administrations::isAdminArea()) {
    $pathinfo = $_SERVER['PATH_INFO'] ?? getenv('PATH_INFO');
    if (empty($pathinfo)) {
        $pathinfo = $_SERVER['ORIG_PATH_INFO'] ?? getenv('ORIG_PATH_INFO');
    }
    $pathinfo = $pathinfo == "" || $pathinfo === 'index.php' ? ($_GET['params'] ?? '') : substr($pathinfo, 1, 4096);
    $params = explode('/', $pathinfo);
    $act = $params[0];
    // POST should only be used for forms. The params variable tells what you're doing.
    // Everything works the same, but SE friendly URLs don't apply here because SE bots don't send POST requests
    // and the URL is always just index.php. Forms can always be posted to SITE_URL.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pathinfo = $_POST['params'] ?? "";
        $params = explode('/', $pathinfo);
        $act = $params[0];
    }
}
PHPArcade\Core::loadLinks();
PHPArcade\Languages::loadLanguage();
if (!isset($_SESSION)) {
    session_start();
} else {
    $user = $_SESSION['user'];
}
$dbconfig = PHPArcade\Core::getDBConfig();
/** @noinspection PhpUndefinedVariableInspection */
$params[2] = $params[2] ?? "";
/* This is the old 'exec.php' from each plugin folder, consolidated and in order */
/** @noinspection PhpUndefinedVariableInspection */
if (($act === 'rssfeeds' || $act === 'rss') && !isset($adminarea) && ($dbconfig['rssenabled'] === 'on')) {
    $type = 'rss';
    switch ($type) {
        case 'rss':
            header('Content-Type: application/rss+xml; charset=' . CHARSET);
            if ($params[1] == 'recent') {
                $array = PHPArcade\Games::getGamesbyReleaseDate_DESC('all');
            }  ?>
            <?xml version='1.0' encoding='UTF-8' ?>
            <rss version="2.0" xmlns="http://purl.org/rss/1.0/modules/content/"
                 xmlns:atom="http://www.w3.org/2005/Atom"
            >
            <channel>
                <title><?php echo $dbconfig['sitetitle']; ?></title><?php echo PHP_EOL; ?>
                <description><?php echo $dbconfig['metadesc']; ?></description>
                <link><?php echo SITE_URL; ?></link>
                <atom:link href='<?php echo $dbconfig['rssfeed']; ?>'
                           rel='self'
                           type='application/rss+xml'/><?php PHP_EOL;
                for ($i = 0; $i < $dbconfig['rssnumlatest']; $i++) {
                    $title = $array[$i]['name'];
                    $desc = $array[$i]['desc'];
                    $link = PHPArcade\Core::getLinkGame($array[$i]['id']);
                    PHP_EOL; ?>
                    <item>
                        <title><?php echo $title; ?></title>
                        <link><?php echo $link; ?></link>
                        <description><![CDATA[<?php echo $desc; ?>]]></description>
                        <guid><?php echo $link; ?></guid>
                        <category>Games</category>
                        <category>Flash Games</category>
                        <category>Online Games</category>
                        <category>Browser Games</category>
                    </item><?php echo PHP_EOL;
                } ?>
            </channel>
            </rss><?php
            break;
        default:
    }
    die();
}
switch ($act) {
    case 'login':
        if ($dbconfig['membersenabled'] === 'on') {
            if ($params[1] === 'login') {
                PHPArcade\Users::userVerifyPassword($_POST['username'], $_POST['password']);
                PHPArcade\Users::userUpdateLastLogin();
            } else {
                if ($params[1] === 'logout') {
                    PHPArcade\Users::userSessionEnd();
                }
            }
        }
        break;
    case 'register':
        require_once INST_DIR . 'vendor/google/recaptcha/src/autoload.php';
        if (isset($_POST['g-recaptcha-response'])) {
            $recaptcha = new \ReCaptcha\ReCaptcha($dbconfig['google_recaptcha_secretkey']);
            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if ($params[1] === 'regdone' && $resp->isSuccess()) {
                if (empty($_POST['username']) || empty($_POST['email'])) {
                    return 'notallfields';
                } else {
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        return 'emailinvalid';
                    } else {
                        if (PHPArcade\Users::userAdd($_POST['username'], $_POST['email'])) {
                            $execstatus = 'success';
                            return 'emailconf';
                        } else {
                            $execstatus = 'failure';
                            return 'usertaken';
                        }
                    }
                }
            } else {
                return 'notallfields';
            }
        } else {
            return 'notallfields';
        }
        break;
    case 'profile':
        if ($params[2] === 'editdone') {
            PHPArcade\Core::doEvent('profileediting');
        }
        // no break
    default:
}
