<?php
declare(strict_types=1);
Core::stopDirectAccess();

class Scores {
    protected $score;
    protected $scores;
    private function __construct() {
    }
    public static function formatScore($number, $dec = 1) { // cents: 0=never, 1=if needed, 2=always
        if (is_numeric($number)) {
            if (!$number) {
                $score['score'] = ($dec == 3 ? '0.00' : '0');
            } else {
                if (floor($number) == $number) {
                    $score['score'] = number_format($number, ($dec == 3 ? 3 : 0));
                } else {
                    $score['score'] = number_format(round($number, 3), ($dec == 0 ? 0 : 3));
                }
            }
        } else { //Should never happen
            $score['score'] = 0;// numeric
        }
        return $score['score'];
    }
    public static function getGameScore($nameid, $sort, $limitnum) {
        /* Strips "-score" from game to be compatible with v2 Arcade Games */
        $nameid = str_replace('-score', "", $nameid);
        switch ($sort) {
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
        $scores = $stmt->fetchAll();
        return $scores;
    }
    public static function getScoreType($string, $ostring) {
        return stristr($ostring, $string) ? true : false;
    }
    public static function updateGameChamp($nameid, $player, $score, $sort, $time){
        /* Figure out who the champion is and their highest score in the GamesChamp table */
        $gamechamp = self::GetGameChampsbyGameNameID($nameid);

        if (self::GetGameChampbyGameNameID_RowCount($nameid) === 0) {
            /* If there is no champion, then INSERT the score into the game champs table */
            self::InsertScoreIntoGameChamps($nameid, $_SESSION['user']['id'], $score, $time);
        } else {
            /* If there is a champion, figure out who it is */
            switch ($sort) {
                /* If the game is a low-score-wins game (e.g. Golf), then update the score */
                case 'ASC':
                    if ($score <= $gamechamp['score']) {
                        self::UpdatePlayerScoreInGameChamps($gamechamp['nameid'], $player, $score, $time);
                    }
                    break;
                default:
                    /* Otherwise, just make sure you have a higher score and then update */
                    if ($score >= $gamechamp['score']) {
                        self::UpdatePlayerScoreInGameChamps($gamechamp['nameid'], $player, $score, $time);
                    }
                    break;
            }
        }
    }
    public static function updateGameScore($nameid, $player, $score, $ip, $time, $sort, $link){
        /* Update games_score table */
        $gamescore = self::GetGameScorebyNameID($nameid, $player);

        /* $gamescore[]:
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

        if (self::GetGameScorebyNameIDRowCount($nameid, $player) === 0) {
            self::InsertScoreIntoGameScore($nameid, $_SESSION['user']['id'], $score, $ip, $time, $link);
        } else {
            switch ($sort) {
                case 'ASC':
                    if ($score < $gamescore['score']) {
                        self::UpdateScoreIntoGameScore($gamescore['nameid'], $gamescore['player'], $score, $ip, $time);
                        Core::loadRedirect(gettext('scoresaved'), $link);
                    } else {
                        Core::loadRedirect(gettext('scorewontsaved'), $link);
                    }
                    break;
                case 'DESC':
                    if ($score >= $gamescore['score']) {
                        self::UpdateScoreIntoGameScore($gamescore['nameid'], $gamescore['player'], $score, $ip, $time);
                        Core::loadRedirect(gettext('scoresaved'), $link);
                    } else {
                        Core::loadRedirect(gettext('scorewontsaved'), $link);
                    }
                    break;
            }
        }
    }
    public static function submitGameScore($nameid = '', $score = 0, $player = '', $ip = '1.1.1.1', $link = '', $sort = 'DESC') {
        if (!isset($_SESSION)) {
            session_start();
        }
        $time = Core::getCurrentDate();
        self::updateGameChamp($nameid, $player, $score, $sort, $time);
        self::updateGameScore($nameid, $player, $score, $ip, $time, $sort, $link);
    }
    public static function GetGameChampsbyGameNameID($nameid) {
        /* Gets all of the champions (highest score for an individual player) for a particular game */
        $sql = 'SELECT * FROM `games_champs` WHERE `nameid` = :nameid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':nameid', $nameid);
            $stmt->execute();
            $champions = $stmt->fetch();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
        return $champions;
    }
    public static function GetGameChampbyGameNameID_RowCount($nameid) {
        $sql = 'SELECT * FROM `games_champs` WHERE `nameid` = :nameid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':nameid', $nameid);
            $stmt->execute();
            $rowcount = $stmt->rowCount();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
        return $rowcount;
    }
    public static function InsertScoreIntoGameChamps($gamenameid, $player, $score, $time) {
        $sql = 'INSERT INTO `games_champs` (`nameid`, `player`, `score`, `date`) 
                    VALUES (:nameid, :player, :score, :currenttime)';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':nameid', $gamenameid);
            $stmt->bindParam(':player', $player);
            $stmt->bindParam(':score', $score);
            $stmt->bindParam(':currenttime', $time);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    public static function UpdatePlayerScoreInGameChamps($gamenameid, $player, $score, $time) {
        $sql = 'UPDATE `games_champs` 
                            SET `score` = :gamescore, 
                                `date` = :currenttime, 
                                `player` = :player 
                            WHERE `nameid` = :gamenameid 
                            LIMIT 1;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':currenttime', $time);
            $stmt->bindParam(':gamenameid', $gamenameid);
            $stmt->bindParam(':gamescore', $score);
            $stmt->bindParam(':player', $player);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    public static function GetGameScorebyNameID($nameid, $player) {
        $sql = 'SELECT * FROM `games_score` WHERE `nameid` = :nameid AND `player` = :player;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':nameid', $nameid);
            $stmt->bindParam(':player', $player);
            $stmt->execute();
            $gamesscore = $stmt->fetch();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
        return $gamesscore;
    }
    public static function GetGameScorebyNameIDRowCount($nameid, $player) {
        $sql = 'SELECT * FROM `games_score` WHERE `nameid` = :nameid AND `player` = :player;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':nameid', $nameid);
            $stmt->bindParam(':player', $player);
            $stmt->execute();
            $rowcount = $stmt->rowCount();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
        return $rowcount;
    }
    public static function InsertScoreIntoGameScore($gamenameid, $player, $score, $ip, $time, $link) {
        $sql = 'INSERT INTO `games_score` (`nameid`, `player`, `score`, `ip`, `date`)
				        VALUES (:nameid, :player, :score, :ip, :currenttime)';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':nameid', $gamenameid);
            $stmt->bindParam(':player', $player);
            $stmt->bindParam(':score', $score);
            $stmt->bindParam(':ip', $ip);
            $stmt->bindParam(':currenttime', $time);
            $stmt->execute();
            $stmt->closeCursor();
            Core::loadRedirect(gettext('scoresaved'), $link);
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
    }
    public static function UpdateScoreIntoGameScore($gamenameid, $player, $score, $ip, $time) {
        $sql = 'UPDATE `games_score`
					        SET	`score` = :gamescore,
						        `ip` = :ip,
						        `date` = :currenttime
					        WHERE
						        `nameid` = :gamenameid AND
						        `player` = :player;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':ip', $ip);
            $stmt->bindParam(':currenttime', $time);
            $stmt->bindParam(':gamenameid', $gamenameid);
            $stmt->bindParam(':gamescore', $score);
            $stmt->bindParam(':player', $player);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    private function __clone() {
    }
}