<?php
declare(strict_types=1);
namespace PHPArcade;
use PDO;

class Scores
{
    public $score;
    public $scores;

    private function __construct()
    {
    }

    public static function deleteGameChamps($gameid){
        $stmt = mySQL::getConnection()->prepare('CALL sp_GamesChamps_DeleteChampsbyGameID(:gameid);');
        $stmt->bindParam(':gameid', $gameid);
        $stmt->execute();
    }
    public static function deleteGameScores($gameid){
        $stmt = mySQL::getConnection()->prepare('CALL sp_GamesScores_DeleteScoresbyGameID(:gameid);');
        $stmt->bindParam(':gameid', $gameid);
        $stmt->execute();
    }
    public static function fixGameChamp($gameid){
        $game = Games::getGameByID($gameid);
        $time = Core::getCurrentDate();
        $link = Core::getLinkGame($game['id']);

        // Fix for bad champions
        /* 	This corrects the "Games Champs Table.  When you delete the games_champs users that
            don't exist anymore, it causes the "champ" to actually be the next person that plays... not the
            actual champ. This code patches that issue by taking the best score for that game and updating the
            champs table.
            $tscore['x'] is the top player in the games list.*/
        if (self::getScoreType('lowhighscore', $game['flags'])) {
            $scores = self::getGameScore($gameid, 'ASC', TOP_SCORE_COUNT);
            $tscores = self::GetGameChampbyGameNameID($gameid); // Fix scores when users are deleted
        } else {
            $scores = self::getGameScore($gameid, 'DESC', TOP_SCORE_COUNT);
            $tscores = self::GetGameChampbyGameNameID($gameid); //Fix champ scores when users are deleted
        }

        $playername = Users::getUserbyID($scores[0]['player']);
        var_dump($playername);
        /* First, check that we don't have an empty scores array (prevents errors on the front-end.
           If the top score in games_champs is not equal to the top score in games_score, correct it */
        if (!empty($scores) && $tscores['score'] != $scores[0]['score']) {
            /* NameID is the game name ID */
            Games::updateGameChamp($scores[0]['nameid'], $scores[0]['player'], $scores[0]['score'], $time);
            self::notifyDiscordFixedScore($scores[0]['nameid'], $playername['username'], $scores[0]['score'], $link);
        }
    }
    public static function formatScore($number, $dec = 1)
    { // cents: 0=never, 1=if needed, 2=always
        if (is_numeric($number))
        {
            if (!$number)
            {
                $score['score'] = ($dec == 3 ? '0.00' : '0');
            } else
            {
                if (floor($number) == $number)
                {
                    $score['score'] = number_format($number, ($dec == 3 ? 3 : 0));
                } else
                {
                    $score['score'] = number_format(round($number, 3), ($dec == 0 ? 0 : 3));
                }
            }
        } else
        { //Should never happen
            $score['score'] = 0;// numeric
        }
        return $score['score'];
    }
    public static function GetGameChampbyGameNameID($nameid)
    {
        /* Gets all of the champions (highest score for an individual player) for a particular game */
        $stmt = mySQL::getConnection()->prepare('CALL sp_GamesChamps_GetChampsbyGame(:nameid);');
        $stmt->bindParam(':nameid', $nameid);
        $stmt->execute();
        return $stmt->fetch();
    }
    public static function GetGameChampbyGameNameID_RowCount($nameid)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_GamesChamps_GetChampsbyGame(:nameid);');
        $stmt->bindParam(':nameid', $nameid);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function getGameScore($nameid, $sort, $limitnum)
    {
        /* Strips "-score" from game to be compatible with v2 Arcade Games */
        $nameid = str_replace('-score', "", $nameid);
        switch ($sort)
        {
            case 'ASC':
                $sql = 'CALL sp_GamesScore_GetScores_ASC(:gamenameid, :limitnum);';
                break;
            case 'DESC':
                $sql = 'CALL sp_GamesScore_GetScores_DESC(:gamenameid, :limitnum);';
                break;
            default:
                $sql = "";
        }
        $stmt = mySQL::getConnection()->prepare($sql);
        $stmt->bindParam(':gamenameid', $nameid);
        $stmt->bindParam(':limitnum', $limitnum, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function GetGameScorebyNameID($nameid, $player)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_GamesScore_ScoresRowCount(:nameid, :player);');
        $stmt->bindParam(':nameid', $nameid);
        $stmt->bindParam(':player', $player);
        $stmt->execute();
        return $stmt->fetch();
    }
    public static function GetGameScorebyGameNameID_RowCount($nameid, $player)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_GamesScore_ScoresRowCount(:nameid, :player);');
        $stmt->bindParam(':nameid', $nameid);
        $stmt->bindParam(':player', $player);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function getScoreType($string, $ostring)
    {
        return stristr($ostring, $string) ? true : false;
    }
    public static function InsertScoreIntoGameScore($gameid, $player, $score, $ip, $time)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_GamesScore_InsertNewGamesScore(:ip, :date, :gamenameid, :gamescore, :gameplayer);');
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':date', $time);
        $stmt->bindParam(':gamenameid', $gameid);
        $stmt->bindParam(':gamescore', $score);
        $stmt->bindParam(':gameplayer', $player);
        $stmt->execute();
    }
    public static function notifyDiscordFixedScore($gamename = '', $player = '', $score = 0, $link)
    {
        $inicfg = Core::getINIConfig();
        $url = $inicfg['environment']['state'] === 'dev' ? $inicfg['webhook']['highscoreURI_Dev'] : $inicfg['webhook']['highscoreURI'];

        $message = '***BUGFIX ALERT!!!*** ' . $player . ' is the rightful champion of ' . $gamename . ' with a score of ' . self::formatScore($score) . '. This has been corrected. ' . $link;

        $data = array(
            "content" => $message,
            "username" => "PHPArcade"
        );
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl);
    }
    public static function notifyDiscordHighScore($gamename = '', $player = '', $score = 0, $link)
    {
        $inicfg = Core::getINIConfig();
        $url = $inicfg['environment']['state'] === 'dev' ? $inicfg['webhook']['highscoreURI_Dev'] : $inicfg['webhook']['highscoreURI'];

        $message = $player . ' is the new champion of _' . $gamename . '_ with a score of ' . self::formatScore($score) . '! Play now at ' . $link;

        $data = array(
            "content" => $message,
            "username" => "PHPArcade"
        );
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl);
    }
    public static function notifyDiscordNewScore($gamename = '', $player = '', $score = 0, $link)
    {
        $inicfg = Core::getINIConfig();
        $url = $inicfg['environment']['state'] === 'dev' ? $inicfg['webhook']['highscoreURI_Dev'] : $inicfg['webhook']['highscoreURI'];

        $message = $player . ' has a new personal high score of ' . self::formatScore($score) . ' in _' . $gamename . '_ ! Play now at ' . $link;

        $data = array(
            "content" => $message,
            "username" => "PHPArcade"
        );
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl);
    }
    public static function submitGameScore($gameid = '', $score = 0, $playerid = '', $ip = '1.1.1.1', $link, $sort = 'DESC')
    {
        $time = Core::getCurrentDate();

        self::updateGameChamp($gameid, $playerid, $score, $sort, $time);
        self::updateGameScore($gameid, $playerid, $score, $ip, $time, $sort);
        return;
    }
    public static function updateGameChamp($gameid = '', $playerid, $score, $sort, $time)
    {
        /* Figure out who the champion is and their highest score in the GamesChamp table */
        $gamechamp = self::GetGameChampbyGameNameID($gameid);
        $game = Games::getGameByID($gameid);
        $playername = ucfirst($_SESSION['user']['name']);
        $link = Core::getLinkGame($game['id']);

        if (self::GetGameChampbyGameNameID_RowCount($gameid) === 0)
        {
            /* If there is no champion, then INSERT the score into the game champs table */
            self::updateGameChamp($gameid, $_SESSION['user']['id'], $score, $time);
        } else
        {
            /* If there is a champion, figure out who it is */
            switch ($sort)
            {
                case 'ASC':
                    /* If the game is a low-score-wins game (e.g. Golf), then update the score */
                    if ($score <= $gamechamp['score'])
                    {
                        self::UpdatePlayerScoreInGameChamps($gameid, $playerid, $score, $time);
                        self::notifyDiscordHighScore($game['name'], $playername, $score, $link);
                    }
                    break;
                case 'DESC':
                    /* Otherwise, just make sure you have a higher score and then update */
                    if ($score >= $gamechamp['score'])
                    {
                        self::UpdatePlayerScoreInGameChamps($gameid, $playerid, $score, $time);
                        self::notifyDiscordHighScore($game['name'], $playername, $score, $link);
                    }
                    break;
                default:
            }
        }
    }
    public static function updateGameScore($gameid = '', $playerid, $score, $ip, $time, $sort)
    {
        $game = Games::getGameByID($gameid);
        $playername = ucfirst($_SESSION['user']['name']);
        $link = Core::getLinkGame($game['id']);


        /* Update games_score table */
        /* $game[]:
            [id]
                [0] = Score ID (PK)
            [nameid]
                [1] = Game name ID (game number)
            [player]
                [2] = Player ID
            [score]
                [3] = Current player's score being submitted
            [ip]
                [4] = Current player's IP address
            [date]
                [5] = Current epoch time */
        if (self::GetGameScorebyGameNameID_RowCount($gameid, $playerid) === 0) {
            self::InsertScoreIntoGameScore($gameid, $playerid, $score, $ip, $time);
            self::notifyDiscordNewScore($game['name'], $playername, $score, $link);
            Core::loadRedirect($link);
        } else {
            $gamescore = self::GetGameScorebyNameID($game['id'], $playerid);
            switch ($sort)
            {
                case 'ASC':
                    if ($score < $gamescore['score'])
                    {
                        self::UpdateScoreIntoGameScore($gameid, $gamescore['player'], $score, $ip, $time);
                        Core::loadRedirect($link);
                    } else
                    {
                        Core::loadRedirect($link);
                    }
                    break;
                case 'DESC':
                    if ($score > $gamescore['score'])
                    {
                        self::UpdateScoreIntoGameScore($gameid, $gamescore['player'], $score, $ip, $time);
                        self::notifyDiscordNewScore($game['name'], $playername, $score, $link);
                        Core::loadRedirect($link);
                    } else
                    {
                        Core::loadRedirect($link);
                    }
                    break;
            }
        }
    }
    public static function UpdatePlayerScoreInGameChamps($gamenameid, $player, $score, $time)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_GamesChamps_UpdateScoresbyGame(:currenttime, :gamenameid, :gamescore, :player);');
        $stmt->bindParam(':currenttime', $time);
        $stmt->bindParam(':gamenameid', $gamenameid);
        $stmt->bindParam(':gamescore', $score);
        $stmt->bindParam(':player', $player);
        $stmt->execute();
    }
    public static function UpdateScoreIntoGameScore($gamenameid, $player, $score, $ip, $time)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_GamesScore_UpdateGamesScore(:ip, :currenttime, :gamenameid, :gamescore, :player);');
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':currenttime', $time);
        $stmt->bindParam(':gamenameid', $gamenameid);
        $stmt->bindParam(':gamescore', $score);
        $stmt->bindParam(':player', $player);
        $stmt->execute();
    }

    /* Scores for players which no longer exist
        SELECT * FROM phparcade.games_score WHERE player NOT IN (SELECT id FROM phparcade.members);
    */

    /* Scores in the champs table for which there are no matching scores in the scores table
        SELECT * FROM phparcade.games_champs gc WHERE nameid NOT IN (SELECT nameid FROM phparcade.games_score);
    */

    /* Games which have scores, but there is no champ in the champs table
        SELECT * FROM phparcade.games_score WHERE nameid NOT IN (SELECT nameid FROM phparcade.games_champs);
    */

    private function __clone()
    {
    }
}
