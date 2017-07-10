<?php
declare(strict_types=1);
Core::stopDirectAccess();
class Core {
    private static $dbconfig;
    protected      $act;
    protected      $config;
    protected      $line;
    protected      $links_arr;
    protected      $string;
    private function __construct() {
    }
    public static function getDBConfig() {
        if (null !== self::$dbconfig) {
            return self::$dbconfig;
        } else {
            try {
                $stmt = mySQL::getConnection()->prepare("CALL sp_Config_Get();");
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            } catch (PDOException $e) {
                echo gettext('Error: ') . ' ' . $e->getMessage() . "\n";
            }
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $rows;
    }
    public static function addAction($action, $event) {
        global $actions_array;
        $actions_array[$event][] = $action;
    }
    public static function doEvent($event) {
        global $actions_array;
        $actions = [];
        if (isset($actions_array[$event])) {
            $actions = $actions_array[$event];
        }
        foreach ($actions as $action) {
            if (is_callable($action)) {
                $action();
            }
        }
    }
    public static function encodeHTMLEntity($string, $params = null) {
        return html_entity_decode($string, $params);
    }
    public static function getAdminGamePageCount() {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGamesNameid();');
            $stmt->execute();
            $get_total_rows = $stmt->rowCount(); //Total Records
            $pages = ceil($get_total_rows / 50);
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $pages;
    }
    public static function getCleanURL($string) {
        $string = preg_replace("/\W/", '-', $string); //Non-word characters, including spaces
        $string = preg_replace("/\-$/", "", $string); //Dashes at end of name (e.g. test-.html)
        $string = preg_replace('/-{2,}/', '-', $string); //Double dashes at end of name (e.g. test--.html)
        return $string;
    }
    public static function getCurrentDate() {
        return time();
    }
	public static function getINIConfig() {
        return parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/../phpArcade.ini', true);
    }
	public static function getLinkCategory($name = 'all', $page = 1) {
        global $links_arr;
        return str_replace('%page%', $page, str_replace('%name%', $name, $links_arr['category']));
    }
	public static function getLinkGame($id = 0) {
        global $gamelist;
        $links_arr = self::loadLinks();
        return str_replace('%name%', $gamelist[$id], str_replace('%id%', $id, $links_arr['game']));
    }
	public static function getLinkLogout() {
        global $links_arr;
        return $links_arr['logout'];
    }
	public static function getLinkPage($id = 0) {
        global $links_arr;
        return str_replace('%id%', $id, $links_arr['page']);
    }
	public static function getLinkRegister() {
        global $links_arr;
        return $links_arr['register'];
    }
	public static function getLinkProfile($userid) {
        global $links_arr;
        if ($userid != 0) {
            $find = $replace = [];
            $user = Users::getUserbyID($userid);
            // Replaces...
            $find[] = '%id%';
            $replace[] = $userid;
            $find[] = '%username%';
            $replace[] = self::getCleanURL($user['username']);
            return str_replace($find, $replace, $links_arr['profile']);
        } else {
            return 0;
        }
    }
	public static function getLinkProfileEdit() {
        global $links_arr;
        return $links_arr['editprofile'];
    }
	public static function getPageMetaData() {
        $dbconfig = Core::getDBConfig();
        global $params;
        switch (true) {
            case (is('game')):
                $game = Games::getGame($params[1]);
                $metadata['metapagetitle'] = $dbconfig['sitetitle'] . ' - ' . $game['name'] . ' ' . gettext('Game');
                $metadata['metapagedesc'] = $game['desc'];
                $metadata['metapagekeywords'] = $game['keywords'];
                break;
            case (is('category')):
                $category = Games::getCategory($params[1]);
                $metadata['metapagetitle'] =
                    $dbconfig['sitetitle'] . ' - ' . $category['name'] . ' ' . gettext('games');
                $metadata['metapagedesc'] = $category['desc'];
                $metadata['metapagekeywords'] = $category['keywords'];
                break;
            case (is('page')):
                $params[1] = $params[1] ?? "";
                $page = Pages::getPage($params[1]);
                $metadata['metapagetitle'] = $dbconfig['sitetitle'] . ' - ' . $page['title'];
                $metadata['metapagedesc'] = $page['description'];
                $metadata['metapagekeywords'] = $page['keywords'];
                break;
            case (is('profile')):
                if ($params[1] != 'edit') {
                    if ($params[2] != 'editdone') {
                        $params[2] = $params[2] ?? "";
                        $user = Users::getUserbyID($params[2]);
                        $metadata['metapagetitle'] =
                            $dbconfig['sitetitle'] . ' - ' . $user['username'] . "'s " . gettext('profile');
                        $metadata['metapagedesc'] = $user['username'] . "'s " . gettext('profile');
                        $metadata['metapagekeywords'] = "";
                    }
                } else {
                    $metadata['metapagetitle'] = "";
                    $metadata['metapagedesc'] = "";
                    $metadata['metapagekeywords'] = "";
                }
                break;
            default:
                $metadata['metapagetitle'] = $dbconfig['sitetitle'];
                $metadata['metapagedesc'] = $dbconfig['metadesc'];
                $metadata['metapagekeywords'] = $dbconfig['metakey'];
        }
        return $metadata;
    }
	public static function getPages($category = '') {
        $dbconfig = Core::getDBConfig();
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetNameidByCategory(:catname);');
            $stmt->bindParam(':catname', $category);
            $stmt->execute();
            $pages = ceil($stmt->rowCount() / $dbconfig['gamesperpage']);
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $pages;
    }
	public static function getPlayCountTotal() {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetPlaycount_Total();');
            $stmt->execute();
            $line = $stmt->fetch();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $line['playcount'];
    }
	public static function loadLinks() {
        global $links_arr, $append, $gamelist;
        /** @noinspection OnlyWritesOnParameterInspection */
        $append = '.html';
        $games = Games::getGamesAllIDsNames();
        $links_arr['game'] = 'game/%id%/%name%';
        foreach ($games as $game) {
            $gamelist[$game['id']] = self::getCleanURL($game['name']);
        }
        // Link data
        $links_arr['category'] = 'category/%name%/%page%';
        $links_arr['confirm'] = 'register/confirm/%code%';
        $links_arr['editprofile'] = 'profile/edit';
        $links_arr['gamescore'] = 'score/%id%/%page%';
        $links_arr['logout'] = 'login/logout';
        $links_arr['page'] = 'page/%id%';
        $links_arr['passwordchange'] = 'login/recover/change/%code%/%username%';
        $links_arr['play'] = 'play/%id%';
        $links_arr['profile'] = 'profile/view/%id%/%username%';
        $links_arr['pwrecover'] = 'login/recover';
        $links_arr['register'] = 'register/register';
        $links_arr['reportgame'] = 'report/%id%';
        $links_arr['rss'] = 'rss/%type%';
        $links_arr['users'] = 'users/%order%/%page%';
        $links_arr = array_map('preappbase', $links_arr);
        return $links_arr;
    }
	public static function loadRedirect($message, $url = 'refurl') {
        $dbconfig = Core::getDBConfig();
        if ($url == 'refurl') {
            $url = $_SERVER['HTTP_REFERER'];
        } ?>
        <html lang="en" xmlns="https://www.w3.org/1999/xhtml">
        <head>
            <meta charset="<?php echo CHARSET; ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo gettext('redirection'); ?></title>
            <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP; ?>"/>
            <!-- This section does lazy loading of the CSS as described here: https://github.com/filamentgroup/loadCSS/ -->
            <link rel="preload" href="<?php echo CSS_BOOTSTRAP_THEME; ?>" as="style" onload="this.rel='stylesheet'">
            <noscript>
                <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP_THEME; ?>"/>
            </noscript>
            <link rel="preload" href="<?php echo SITE_THEME_DIR; ?>" as="style" onload="this.rel='stylesheet'">
            <noscript>
                <link rel="stylesheet" href="<?php echo SITE_THEME_DIR; ?>"/>
            </noscript>
            <link rel="preload" href="<?php echo CSS_FONTAWESOME; ?>" as="style" onload="this.rel='stylesheet'">
            <noscript>
                <link rel="stylesheet" href="<?php echo CSS_FONTAWESOME; ?>"/>
            </noscript>
            <meta name="robots" content="noindex,nofollow"/>
            <meta http-equiv="refresh" content="1;URL=<?php echo $url; ?>"/>
        </head>
        <body><?php
            if ($dbconfig['ga_enabled'] === 'on') {
                include_once INST_DIR . 'includes/js/Google/googletagmanager.php';
            } ?>
            <div class="col-md-12">
                <div class="panel-body text-center">
                    <p><?php echo $message; ?></p>
                    <p>If you are not redirected, please <a href="<?php echo $url; ?>">click here</a>.</p>
                </div>
            </div>
        </body>
        </html><?php
        die();
    }
	public static function stopDirectAccess(){
        if (count(get_included_files()) === 1) {
            return http_response_code(403) . die('Direct access not permitted.');
        }
        return true;
    }
	public static function returnStatusCode($statuscode) {
        return http_response_code($statuscode);
    }
	public static function showCategoryList($categories) {
        $i = 0;
        foreach ($categories as $category) {
            ++$i;
            $link = self::getLinkCategory($category['name'], 1); ?>
            <li>
                <a href="<?php echo $link; ?>">
                    <?php echo $category['name']; ?>
                </a>
            </li><?php
        }
    }
	public static function showError($text, $glyph = 'ambulance') {
        ?>
        <div class="alert alert-danger" role="alert">
        <span class="fa fa-<?php echo $glyph; ?> fa-2x text-left" aria-hidden="true"></span>
        <strong><?php echo gettext('error') ?></strong>
        <?php echo $text; ?>
        </div><?php
    }
	public static function showGlyph($glyph, $size = '1x', $hidden = 'true') {
        return "<i class='fa fa-$glyph fa-$size' aria-hidden='$hidden'></i>";
    }
	public static function showInfo($text, $glyph = 'info') { ?>
        <div class="alert alert-info" role="alert">
        <span class="fa fa-<?php echo $glyph; ?> fa-2x text-left" aria-hidden="true"></span>
        <strong><?php echo gettext('info') ?></strong>
        <?php echo $text; ?>
        </div><?php
    }
	public static function showSuccess($text, $glyph = 'check') {
        ?>
        <div class="alert alert-success" role="alert">
        <span class="fa fa-<?php echo $glyph; ?> fa-2x text-left" aria-hidden="true"></span>
        <strong><?php echo gettext('success') ?></strong>
        <?php echo $text; ?>
        </div><?php
    }
	public static function showWarning($text, $glyph = 'warning') { ?>
        <div class="alert alert-warning" role="alert">
        <span class="fa fa-<?php echo $glyph; ?> fa-2x text-left" aria-hidden="true"></span>
        <strong><?php echo gettext('warning') ?></strong>
        <?php echo $text; ?>
        </div><?php
    }
    private function __clone() {
    }
}
function load_theme() {
    global $config;
    $dbconfig = Core::getDBConfig();
    $config['themeinc'] = INST_DIR . 'plugins/site/themes/' . $dbconfig['theme'] . '/index.php';
}
function load_admin_theme() {
    global $config;
    define('SITE_URL_ADMIN_THEME', SITE_URL . 'plugins/site/themes/admin/');
    $config['themeinc'] = INST_DIR . 'plugins/site/themes/admin/index.php';
}
function is($location) {
    global $act;
    if ($act == "" || !isset($act)) {
        $act = 'home';
    }
    return $act == $location;
}
function preappbase($string) {
    global $prepend, $append;
    return $string != SITE_URL_ADMIN ? SITE_URL . $prepend . $string . $append : $string;
}