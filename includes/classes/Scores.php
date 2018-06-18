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
    public static function InsertScoreIntoGameChamps($gamenameid, $player, $score, $time)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_GamesChamps_InsertScoresbyGame(:currenttime, :nameid, :score, :player);');
        $stmt->bindParam(':currenttime', $time);
        $stmt->bindParam(':nameid', $gamenameid);
        $stmt->bindParam(':score', $score);
        $stmt->bindParam(':player', $player);
        $stmt->execute();
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
    public static function notifyDiscordHighScore($gamename = '', $player = '', $score = 0, $link)
    {
        $inicfg = Core::getINIConfig();
        $url = $inicfg['environment']['state'] === 'dev' ? $inicfg['webhook']['highscoreURI_Dev'] : $inicfg['webhook']['highscoreURI'];

        $message = $player . ' is the new champion of _' . $gamename . '_ with a score of ' . $score . '! Play now at ' . $link;

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

        $message = $player . ' has a new personal high score of ' . $score . ' in _' . $gamename . '_ ! Play now at ' . $link;

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
            self::InsertScoreIntoGameChamps($gameid, $_SESSION['user']['id'], $score, $time);
            self::notifyDiscordHighScore($game['name'], $playername, $score, $link);
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

    private function __clone()
    {
    }
}
