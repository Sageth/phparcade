<?php
declare(strict_types=1);
Core::stopDirectAccess();

class Games {
    protected $category;
    protected $game;
    protected $games;
    protected $rowcount;
    protected $select;
    private function __construct() {
    }
	public static function addGame($id, $nameid, $gameorder, $gwidth, $gheight, $type, $playcount, $release_date) {
        $time = Core::getCurrentDate();
        $sql = 'INSERT INTO `games`
						(`id`, `nameid`, `name`, `desc`, `instructions`, `cat`, `order`, `width`, `height`,
						 `type`, `playcount`, `flags`, `keywords`, `time`, `release_date`, `customcode`)
					VALUES 
						(:gameid, :gamenameid,:gamename,:gamedesc,:gameinstructions,:gamecat,:gameorder,
						 :gamewidth, :gameheight, :gametype, :gameplaycount, :gameflags, :gamekeywords,
						 :gametime, :gamereleasedate, :gamecustomcode);';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':gameid', $id);
            $stmt->bindParam(':gamenameid', $nameid);
            $stmt->bindParam(':gamename', $_POST['name']);
            $stmt->bindParam(':gamedesc', $_POST['desc']);
            $stmt->bindParam(':gameinstructions', $_POST['instructions']);
            $stmt->bindParam(':gamecat', $_POST['cat']);
            $stmt->bindParam(':gameorder', $gameorder);
            $stmt->bindParam(':gamewidth', $gwidth);
            $stmt->bindParam(':gameheight', $gheight);
            $stmt->bindParam(':gametype', $type);
            $stmt->bindParam(':gameplaycount', $playcount);
            $stmt->bindParam(':gameflags', $_POST['flags']);
            $stmt->bindParam(':gamekeywords', $_POST['keywords']);
            $stmt->bindParam(':gametime', $time);
            $stmt->bindParam(':gamereleasedate', $release_date);
            $stmt->bindParam(':gamecustomcode', $_POST['customcode']);
            $stmt->execute();
            Core::showSuccess(gettext('addsuccess'));
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
	public static function deleteCategory($id) {
        /* Uses Index */
        $sql = 'DELETE FROM `categories` WHERE `id` = :catid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':catid', $id);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
	public static function deleteGame($id) {
        /* Uses Index */
        $sql = 'DELETE FROM `games` WHERE `id` = :gameid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':gameid', $id);
            $stmt->execute();
            Games::updateGameOrder();
            Core::showSuccess(gettext('deletesuccess'));
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
	public static function updateGameOrder() {
        $games = self::getGames('all', 0, 10000, '-all', -1);
        $i = 1;
        /* TODO: Make this use index */
        $sql = 'UPDATE `games` SET `order` = :gameorder WHERE `id` = :gameid;';
        $stmt = mySQL::getConnection()->prepare($sql);
        foreach ($games as $game) {
            try {
                $stmt->bindParam(':gameorder', $i);
                $stmt->bindParam(':gameid', $game['id']);
                $stmt->execute();
                $stmt->closeCursor();
                return;
            } catch (PDOException $e) {
                Core::showError($e->getMessage());
            }
            ++$i;
        }
    }
	public static function getGames($category, $limitstart, $limitend, $page = '-all-', $gamesperpage) {
        /* Typical values are:
            Category = "all"
            Limit Start = 0
            Limit End = 10,
            Page = "-all-",
            Games Per Page = 30 */
        $dbconfig = Core::getDBConfig();
        $time = Core::getCurrentDate();
        if ($gamesperpage == -1 && isset($page)) {
            $gamesperpage = $dbconfig['gamesperpage'];
        }
        if ($page != '-all-') {
            if (!is_numeric($page)) {
                $page = 1;
            }
            $limitstart = ($page - 1) * $gamesperpage;
            $limitend = $gamesperpage;
        }
        if ($category == 'all') {
            /* Carousel and admin "manage media" sections */
            if (Administrations::isAdminArea()) {
                /* TODO: Make this use index */
                $sql =
                    'SELECT * FROM `games` WHERE `release_date` != :release_date ORDER BY `name` ASC LIMIT :limitstart,:limitend;';
            } else {
                /* Uses index */
                $sql =
                    'SELECT * FROM `games` WHERE `release_date` <= :release_date ORDER BY `playcount` DESC LIMIT :limitstart,:limitend;';
            }
            try {
                $stmt = mySQL::getConnection()->prepare($sql);
                $stmt->bindParam(':release_date', $time);
                $stmt->bindParam(':limitstart', $limitstart);
                $stmt->bindParam(':limitend', $limitend);
                $stmt->execute();
                $games = $stmt->fetchAll();
                $stmt->closeCursor();
            } catch (PDOException $e) {
                echo gettext('error') . ' ' . $e->getMessage() . "\n";
            }
        } else {
            /* Category page */
            /* TODO: Make this use index */
            $sql =
                "SELECT * FROM `games` WHERE `cat` = :category AND `active` = 'Yes' AND release_date <= :release_date ORDER BY `name` ASC LIMIT :limitstart,:limitend;";
            try {
                $stmt = mySQL::getConnection()->prepare($sql);
                $stmt->bindParam(':release_date', $time);
                $stmt->bindParam(':category', $category);
                $stmt->bindParam(':limitstart', $limitstart);
                $stmt->bindParam(':limitend', $limitend);
                $stmt->execute();
                $games = $stmt->fetchAll();
                $stmt->closeCursor();
            } catch (PDOException $e) {
                echo gettext('error') . ' ' . $e->getMessage() . "\n";
            }
        }
        /** @noinspection PhpUndefinedVariableInspection */
        foreach ($games as $game) {
            $game['name'] = htmlentities($game['name']);
            $game['cat'] = htmlentities($game['cat']);
            $game['desc'] = Core::encodeHTMLEntity($game['desc'], ENT_QUOTES);
            $game['instructions'] = Core::encodeHTMLEntity($game['instructions'], ENT_QUOTES);
            $game['keywords'] = Core::encodeHTMLEntity($game['keywords'], ENT_QUOTES);
        }
        return $games;
    }
	public static function getCategory($name) {
        /* Uses Index */
        $sql = 'SELECT * FROM `categories` WHERE `name` = :categoryname;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':categoryname', $name);
            $stmt->execute();
            $category = $stmt->fetch();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $category;
    }
	public static function getCategoryID($id) {
        /* Uses Index */
        $sql = 'SELECT * FROM `categories` WHERE `id` = :categoryid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':categoryid', $id);
            $stmt->execute();
            $category = $stmt->fetch();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $category;
    }
	public static function getCategoryIDMax() {
        /* Gets max order and sets the new category to be max(order)+1 */
        /* Uses Index */
        $sql = 'SELECT MAX(`order`) AS maxOrder FROM `categories`;';
        $stmt = mySQL::getConnection()->prepare($sql);
        $stmt->execute();
        $order = $stmt->fetch();
        $order = $order['maxOrder'] + 1;
        $stmt->closeCursor();
        return $order;
    }
	public static function getCategorySelect($name, $prev = null) {
        $categories = self::getCategories('ASC');
        $select = "<select class='form-control' name='" . $name . "'>";
        if ($prev != '-nocat-') {
            $select .= "<option value='" . $prev . "'>" . $prev . '</option>';
        }
        foreach ($categories as $category) {
            $select .= "<option value='" . $category['name'] . "'>" . $category['name'] . '</option>';
        }
        $select .= '</select>';
        return $select;
    }
	public static function getCategories($sort) {
        switch ($sort) {
            case 'DESC':
                $sql = 'SELECT * FROM `categories` ORDER BY `order` DESC;'; //Uses index
                break;
            case 'ASC':
                $sql = 'SELECT * FROM `categories` ORDER BY `order` ASC;'; //Uses index
                break;
            default:
                $sql = "";
        }
        $stmt = mySQL::getConnection()->prepare($sql);
        $stmt->bindParam(':parentcat', $parent);
        $stmt->execute();
        $category = $stmt->fetchAll();
        $stmt->closeCursor();
        return $category;
    }
	public static function getGame($id) {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGameByID(:gameid);');
            $stmt->bindParam(':gameid', $id);
            $stmt->execute();
            $game = $stmt->fetch();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        if (isset($game['nameid'])) {
            // Changes to type 'CustomCode' if there is any text in the customcode field
            if (!empty($game['customcode'])) {
                $game['type'] = 'CustomCode';
            }
            if ($game['active'] == 'No' && !Administrations::isAdminArea()
            ) { /*// If you're in the admin area, don't return an error status */
                Core::returnStatusCode(503); ?>
                <h1><?php echo gettext('503status'); ?></h1>
                <h2><?php echo gettext('503desc'); ?></h2><?php
                die();
            } elseif ($game['active'] == 'Yes') {
                switch ($game['type']) {
                    case 'SWF':
                        $game['filename'] = $game['nameid'] . '.swf';
                        $game['code'] =
                            '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' . $game['width'] .
                            '" height="' . $game['height'] . '">';
                        $game['code'] .= '<param name="movie" value="' . SWF_URL . $game['filename'] . '" />';
                        $game['code'] .= '<param name="allowscriptaccess" value="always">';
                        $game['code'] .= '<!--[if !IE]>-->';
                        $game['code'] .= '<object type="application/x-shockwave-flash" data="' . SWF_URL .
                                         $game['filename'] . '" width="' . $game['width'] . '" height="' .
                                         $game['height'] . '">';
                        $game['code'] .= '<!--<![endif]-->';
                        $game['code'] .= 'Sorry, you must have flash to <strong>play ' . $game['name'] . '</strong>';
                        $game['code'] .= '<!--[if !IE]>-->';
                        $game['code'] .= '</object>';
                        $game['code'] .= '<!--<![endif]-->';
                        $game['code'] .= '</object>';
                        $game['filename'] = SWF_URL . $game['filename'];
                        break;
                    case 'CustomCode':
                        $game['customcode'] = Core::encodeHTMLEntity($game['customcode'], ENT_QUOTES);
                        break;
                    default:
                }
            }
            $game['instructions'] = Core::encodeHTMLEntity($game['instructions'], ENT_HTML5);
            $game['desc'] = Core::encodeHTMLEntity($game['desc'], ENT_HTML5);
        }
        return $game;
    }
	public static function getGameByNameID($nameid) {
        /* TODO: Make this use index */
        $sql = 'SELECT * FROM `games` WHERE `nameid` = :gamenameid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':gamenameid', $nameid);
            $stmt->execute();
            $game = $stmt->fetch();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $game;
    }
	public static function getGameCountByNameID($nameid) {
        /* TODO: Make this use index */
        $sql = 'SELECT * FROM `games` WHERE `nameid` = :gamenameid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':gamenameid', $nameid);
            $stmt->execute();
            $rowcount = $stmt->rowCount();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $rowcount;
    }
	public static function getGamesAllIDsNames() {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetIDandName();');
            $stmt->execute();
            $games = $stmt->fetchAll();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $games;
    }
	public static function getGamesChamp($playerid) {
        /* TODO: Make this use index */
        $sql = 'SELECT `nameid`,`player` FROM `games_champs` WHERE `player` = :playerid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':playerid', $playerid);
            $stmt->execute();
            $games = $stmt->fetchAll();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $games;
    }
	public static function getGamesCount($category) {
        $time = Core::getCurrentDate();
        if ($category == 'all') {
            /* TODO: Make this use index */
            $sql = "SELECT `id` FROM `games` WHERE `active` = 'Yes' AND release_date <= :releasedate;";
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':releasedate', $time);
        } else {
            /* TODO: Make this use index */
            $sql =
                "SELECT `id` FROM `games` WHERE `active` = 'Yes' AND release_date <= :releasedate AND cat = :category;";
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':releasedate', $time);
        }
        $stmt->execute();
        $rowcount = $stmt->rowCount();
        $stmt->closeCursor();
        return $rowcount;
    }
	public static function getGamesHomePage() {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetRandom8();');
            $stmt->execute();
            $games = $stmt->fetchAll();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $games;
    }
	public static function getGamesInactive($active = 'No') {
        /* TODO: Make this use index */
        $sql = 'SELECT * FROM `games` WHERE `active`= :active;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':active', $active);
            $stmt->execute();
            $games = $stmt->fetchAll();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $games;
    }
	public static function getGamesInactiveCount($active = 'No') {
        /* TODO: Make this use index */
        $sql = 'SELECT * FROM `games` WHERE `active`= :active;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':active', $active);
            $stmt->execute();
            $rowcount = $stmt->rowCount();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $rowcount;
    }
	public static function getGamesBroken() {
        $yes = 'Yes';
        /* TODO: Make this use index */
        $sql = 'SELECT `id` FROM `games` WHERE `broken` = :broken;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':notactive', $yes);
            $stmt->execute();
            $games = $stmt->fetchAll();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $games;
    }
	public static function getGamesBrokenCount() {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetBrokenByID();');
            $stmt->execute();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $stmt->rowCount();
    }
	public static function getGamesLikeThis() {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetRandom4();');
            $stmt->execute();
            $games = $stmt->fetchAll();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $games;
    }
	public static function insertCategory($id = null, $name, $description, $keywords, $order, $type) {
        /* TODO: Make this use index */
        $sql = 'INSERT INTO `categories` ( `id` , `name` , `desc` , `keywords`, `order` , `type`)
					VALUES (:catid, :catname, :catdesc, :catkeywords, :catorder, :cattype);';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':catid', $id);
            $stmt->bindParam(':catname', $name);
            $stmt->bindParam(':catdesc', $description);
            $stmt->bindParam(':catkeywords', $keywords);
            $stmt->bindParam(':catorder', $order);
            $stmt->bindParam(':cattype', $type);
            $stmt->execute();
            $stmt->closeCursor();
            Core::showSuccess(gettext('addsuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
	public static function updateCategory($id, $name, $type, $description, $keywords) {
        /* TODO: Make this use index */
        $sql =
            'UPDATE `categories` SET `name` = :catname, `type` = :cattype, `desc` = :catdesc, `keywords` = :catkeywords WHERE `id` = :catid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':catname', $name);
            $stmt->bindParam(':cattype', $type);
            $stmt->bindParam(':catdesc', $description);
            $stmt->bindParam(':catkeywords', $keywords);
            $stmt->bindParam(':catid', $id);
            $stmt->execute();
            $stmt->closeCursor();
            Core::showSuccess(gettext('updatesuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
	public static function updateCategoryOrder($categories, $i = 1) {
        /* TODO: Make this use index */
        $sql = 'UPDATE `categories` SET `order` = :catorder WHERE `id` = :catid;';
        $stmt = mySQL::getConnection()->prepare($sql);
        foreach ($categories as $category) {
            $category['order'] = $i;
            try {
                $stmt->bindParam(':catid', $category['id']);
                $stmt->bindParam(':catorder', $category['order']);
                $stmt->execute();
                ++$i;
                $stmt->closeCursor();
            } catch (PDOException $e) {
                Core::showError($e->getMessage());
            }
        }
    }
	public static function updateGame($id) {
        /* TODO: Make this use index */
        $sql = 'UPDATE `games`
					SET
						`name`      = :gamename,
						`nameid`    = :gamenameid,
						`desc`      = :gamedesc,
						`cat`       = :gamecat,
						`keywords`  = :gamekeywords,
						`flags`     = :gameflags,
						`instructions` = :gameinstructions,
						`customcode`= :gamecustomcode,
						`width`     = :gamewidth,
						`height`    = :gameheight,
						`active`    = :gameactive,
						`release_date` = :gamerelease
					WHERE
						`id` = :gameid;';
        try {
            $_POST['active'] = array_key_exists('active', $_POST) ? 'Yes' : 'No';
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':gamename', $_POST['name']);
            $stmt->bindParam(':gamenameid', $_POST['nameid']);
            $stmt->bindParam(':gamedesc', $_POST['desc']);
            $stmt->bindParam(':gamecat', $_POST['cat']);
            $stmt->bindParam(':gamekeywords', $_POST['keywords']);
            $stmt->bindParam(':gameflags', $_POST['flags']);
            $stmt->bindParam(':gameinstructions', $_POST['instructions']);
            $stmt->bindParam(':gamecustomcode', $_POST['customcode']);
            $stmt->bindParam(':gamewidth', $_POST['width']);
            $stmt->bindParam(':gameheight', $_POST['height']);
            $stmt->bindParam(':gameactive', $_POST['active']);
            $stmt->bindParam(':gamerelease', $_POST['release_date']);
            $stmt->bindParam(':gameid', $id);
            $stmt->execute();
            Core::showSuccess(gettext('updatesuccess'));
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
	public static function updateGameChamp($tplayerid, $tplayername, $tscore, $time, $gameid) {
        /* TODO: Make this use index */
        $sql =
            'UPDATE `games_champs` SET `nameid`= :top_nameid, `player`= :top_user, `score` = :top_score, `date`  = :curr_time WHERE `nameid` =  :game_id	LIMIT 1';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':top_nameid', $tplayerid);
            $stmt->bindParam(':top_user', $tplayername);
            $stmt->bindParam(':top_score', $tscore);
            $stmt->bindParam(':curr_time', $time);
            $stmt->bindParam(':game_id', $gameid);
            $stmt->execute();
            $stmt->closeCursor();
            return;
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
    }
	public static function updateGamePlaycount($gameid) {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_UpdateGamePlaycountbyID(:gameid);');
            $stmt->bindParam(':gameid', $gameid);
            $stmt->execute();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        return;
    }
    private function __clone() {
    }
}