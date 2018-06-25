<?php
declare(strict_types=1);
namespace PHPArcade;

class Games
{
    protected $category;
    protected $game;
    protected $games;
    protected $rowcount;
    protected $select;
    private function __construct()
    {
    }
    public static function addGame($id, $nameid, $gameorder, $gwidth, $gheight, $type, $playcount, $release_date)
    {
        $time = Core::getCurrentDate();
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Games_AddGames(:gameid, :gamenameid, :gamename, :gamedesc, :gameinstructions, :gamecat, :gameorder, :gamewidth, :gameheight, :gametype, :gameplaycount, :gameflags, :gamekeywords, :gametime, :gamereleasedate, :gamecustomcode);');
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
    }
    public static function convertImage($fromImage, $nameid)
    {
        $dbconfig = Core::getDBConfig();
        //Load the file and convert to PNG
        try
        {
            (new \claviska\SimpleImage())->fromFile($fromImage)->resize($dbconfig['twidth'], $dbconfig['theight'])->toFile(IMG_DIR .
                $nameid, 'image/png');
        } catch (\Exception $e)
        {
            Core::showError('Unable to convert', 'ambulance');
        }
        return;
    }
    public static function deleteCategory($id)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Categories_DeleteCategorybyID(:catid);');
        $stmt->bindParam(':catid', $id);
        $stmt->execute();
    }
    public static function deleteGame($id)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_DeleteGamebyID(:gameid);');
        $stmt->bindParam(':gameid', $id);
        $stmt->execute();
        self::updateGameOrder();

        Scores::deleteGameChamps($id);
        Scores::deleteGameScores($id);
        Core::showSuccess(gettext('deletesuccess'));
    }
    public static function updateGameOrder()
    {
        $games = self::getGames('all', 0, 10000, '-all', -1);
        $i = 1;
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_UpdateGameOrder(:gameorder, :gameid);');
        foreach ($games as $game) {
            $stmt->bindParam(':gameorder', $i);
            $stmt->bindParam(':gameid', $game['id']);
            $stmt->execute();
            ++$i;
        }
    }
    public static function getGames($category, $limitstart, $limitend, $page = '-all-', $gamesperpage)
    {
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
        switch ($category) {
            case 'all':
                /* Carousel and admin "manage media" sections */
                /* TODO: Break this out into separate functions */
                $sql =
                    Administrations::isAdminArea() ? 'CALL sp_Games_GetGamesByReleasedate_ASC(:release_date, :limitstart, :limitend);' : 'CALL sp_Games_GetGamesByReleasedate_DESC(:release_date, :limitstart, :limitend);';
                $stmt = mySQL::getConnection()->prepare($sql);
                $stmt->bindParam(':release_date', $time);
                $stmt->bindParam(':limitstart', $limitstart);
                $stmt->bindParam(':limitend', $limitend);
                $stmt->execute();
                $games = $stmt->fetchAll();
                break;
            default:
                /* Category page */
                $stmt =
                    mySQL::getConnection()->prepare('CALL sp_Games_GetGamesByCategory_ASC(:category, :release_date, :limitstart, :limitend);');
                $stmt->bindParam(':category', $category);
                $stmt->bindParam(':release_date', $time);
                $stmt->bindParam(':limitstart', $limitstart);
                $stmt->bindParam(':limitend', $limitend);
                $stmt->execute();
                $games = $stmt->fetchAll();
                break;
        }
        return $games;
    }
    public static function getCategory($name)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Categories_GetCategoryByName(:categoryname);');
        $stmt->bindParam(':categoryname', $name);
        $stmt->execute();
        return $stmt->fetch();
    }
    public static function getCategoryID($id)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Categories_GetCategoryByID(:categoryid);');
        $stmt->bindParam(':categoryid', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public static function getCategoryIDMax()
    {
        /* Gets max order and sets the new category to be max(order)+1 */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Categories_GetCategoryMaxID();');
        $stmt->execute();
        $order = $stmt->fetch();
        return $order['maxOrder'] + 1;
    }
    public static function getCategorySelect($name, $prev = null)
    {
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
    public static function getCategories($sort)
    {
        switch ($sort) {
            case 'DESC':
                $sql = 'CALL sp_Categories_GetCategoriesByOrder_DESC();';
                break;
            case 'ASC':
                $sql = 'CALL sp_Categories_GetCategoriesByOrder_ASC();';
                break;
            default:
                $sql = "";
        }
        $stmt = mySQL::getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getGameModal()
    {
        ?>
        <!--suppress ALL -->
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h4 class="modal-title">Notice Regarding Flash</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-default">
                            Notice: All of the major browsers are ending support of Adobe Flash, so you will need to
                            enable Flash to have the best experience while we add more mobile-friendly games and apps.
                        </p>
                        <p class="text-danger">
                            Please note: We <strong>ONLY</strong> serve flash games from <?php echo SITE_URL; ?>.  The
                            settings below will only allow flash for <?php echo SITE_URL; ?>, which will help ensure your
                            security.
                        </p>
                        <p class="text-default">
                            Alternatively, you may play our HTML5 games which do not require Flash and are also able
                            to be played on mobile.  Unfortunately, Flash is not available on mobile devices.
                        </p>
                        <div class="pull-left">
                            <a class="btn btn-md btn-info"
                               href="https://helpx.adobe.com/flash-player/kb/enabling-flash-player-firefox.html"
                               target="_blank"
                               rel="noopener">
                                Enable Flash - <i class="fa fa-firefox" aria-hidden="true"></i> Firefox
                            </a>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-md btn-info"
                               href="<?php echo Core::getLinkPage(6); ?>"
                               target="_blank"
                               rel="noopener">
                                Enable Flash - <i class="fa fa-chrome" aria-hidden="true"></i> Chrome
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div><?php
    }
    public static function getGame($id)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGameByID(:gameid);');
        $stmt->bindParam(':gameid', $id);
        $stmt->execute();
        $game = $stmt->fetch();
        if (isset($game['nameid'])) {
            // Changes to type 'CustomCode' if there is any text in the customcode field
            if (!empty($game['customcode'])) {
                $game['type'] = 'CustomCode';
            }
            if ($game['active'] === 'No' &&
                !Administrations::isAdminArea()) { /*// If you're in the admin area, don't return an error status */
                Core::returnStatusCode(503); ?>
                <h1><?php echo gettext('503status'); ?></h1>
                <h2><?php echo gettext('503desc'); ?></h2><?php
                die();
            } elseif ($game['active'] === 'Yes') {
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
                        $game['code'] .= '<button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#myModal">';
                        $game['code'] .= 'Is ' . $game['name'] . ' not loading? Fix it here!';
                        $game['code'] .= '</button>';
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
    public static function getGameByID($id){
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGameByID(:gameid);');
        $stmt->bindParam(':gameid', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public static function getGameByNameID($nameid)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGamebyNameid(:gamenameid);');
        $stmt->bindParam(':gamenameid', $nameid);
        $stmt->execute();
        return $stmt->fetch();
    }
    public static function getGameCountByNameID($nameid)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGamebyNameid(:gamenameid);');
        $stmt->bindParam(':gamenameid', $nameid);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function getGamesAllIDsNames()
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetIDandName();');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getGamesChamp($playerid)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_GamesChamps_GetPlayerNameID(:playerid);');
        $stmt->bindParam(':playerid', $playerid);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getGamesCount($category)
    {
        $time = Core::getCurrentDate();
        switch ($category) {
            case 'all':
                $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGames_ActivebyReleaseDate(:releasedate);');
                $stmt->bindParam(':releasedate', $time);
                break;
            default:
                $stmt =
                    mySQL::getConnection()->prepare('CALL sp_Games_GetGames_ActivebyCategory(:releasedate, :category');
                $stmt->bindParam(':category', $category);
                $stmt->bindParam(':releasedate', $time);
                break;
        }
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function getGamesHomePage()
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetRandom8();');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getGamesInactive($active = 'No')
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGames_Active(:active);');
        $stmt->bindParam(':active', $active);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getGamesInactiveCount($active = 'No')
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetGames_Active(:active);');
        $stmt->bindParam(':active', $active);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function getGamesBrokenCount()
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetBrokenByID();');
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function getGamesLikeThis()
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_GetRandom4();');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function insertCategory($id = null, $name, $description, $keywords, $order, $type)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Categories_InsertCategory(:catid, :catname, :catdesc, :catkeywords, :catorder, :cattype);');
        $stmt->bindParam(':catid', $id);
        $stmt->bindParam(':catname', $name);
        $stmt->bindParam(':catdesc', $description);
        $stmt->bindParam(':catkeywords', $keywords);
        $stmt->bindParam(':catorder', $order);
        $stmt->bindParam(':cattype', $type);
        $stmt->execute();
        Core::showSuccess(gettext('addsuccess'));
    }
    public static function updateCategory($id, $name, $type, $description, $keywords)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Categories_UpdateCategory(:catid, :catname, :catdesc, :catkeywords, :cattype);');
        $stmt->bindParam(':catid', $id);
        $stmt->bindParam(':catname', $name);
        $stmt->bindParam(':catdesc', $description);
        $stmt->bindParam(':catkeywords', $keywords);
        $stmt->bindParam(':cattype', $type);
        $stmt->execute();
        Core::showSuccess(gettext('updatesuccess'));
    }
    public static function updateCategoryOrder($categories, $i = 1)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Categories_UpdateOrder(:catorder, :catid);');
        foreach ($categories as $category) {
            $category['order'] = $i;
            $stmt->bindParam(':catid', $category['id']);
            $stmt->bindParam(':catorder', $category['order']);
            $stmt->execute();
            ++$i;
        }
    }
    public static function updateGame($id)
    {
        $_POST['active'] = array_key_exists('active', $_POST) ? 'Yes' : 'No';
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Games_UpdateGame(:gamename, :gamenameid, :gamedesc, :gamecat, :gamekeywords, :gameflags, :gameinstructions, :gamecustomcode, :gamewidth, :gameheight, :gameactive, :gamerelease, :gameid);');
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
    }
    public static function updateGameChamp($tgameid, $tplayername, $tscore, $time)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_GameChamps_UpdateChamp(:top_nameid, :top_player, :top_score, :curr_time);');
        $stmt->bindParam(':top_nameid', $tgameid);
        $stmt->bindParam(':top_player', $tplayername);
        $stmt->bindParam(':top_score', $tscore);
        $stmt->bindParam(':curr_time', $time);
        $stmt->execute();
        return;
    }
    public static function updateGamePlaycount($gameid)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_UpdateGamePlaycountbyID(:gameid);');
        $stmt->bindParam(':gameid', $gameid);
        $stmt->execute();
        return;
    }
    private function __clone()
    {
    }
}
