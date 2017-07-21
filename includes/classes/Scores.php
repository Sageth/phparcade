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
    public static function submitGameScore($nameid = '', $score = 0, $player = '', $ip = '1.1.1.1', $link = '', $sort = 'DESC') {
        /* TODO: Separate this monster into different functions */
        if (!isset($_SESSION)) {
            session_start();
        }
        $time = Core::getCurrentDate();
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_GameChamps_SelectAll(:nameid);');
            $stmt->bindParam(':nameid', $nameid);
            $stmt->execute();
            $nkq = $stmt->fetch();
            $rowcount = $stmt->rowCount();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
        /* If there is no champion, insert it */
        if ($rowcount === 0) {
            $stmt->nextRowset();
            try {
                $stmt = mySQL::getConnection()->prepare('CALL sp_GameChamps_AddScore(:nameid, :player, :score, :date);');
                $stmt->bindParam(':nameid', $nameid);
                $stmt->bindParam(':player', $_SESSION['user']['id']);
                $stmt->bindParam(':score', $score);
                $stmt->bindParam(':date', $time);
                $stmt->execute();
            } catch (PDOException $e) {
                Core::showError($e->getMessage());
            }
        } else {
            /* If there is a champion, figure out who it is */
            $post = false;
            if ($sort == 'ASC') {
                if ($score <= $nkq['score']) {
                    $post = true;
                }
            } else {
                if ($score >= $nkq['score']) {
                    $post = true;
                }
            }
            if ($post == true) {
                $stmt->nextRowset();
                try {
                    $stmt = mySQL::getConnection()->prepare('CALL sp_GameChamps_UpdateScore(:nameid, :player, :score, :date);');
                    $stmt->bindParam(':gamenameid', $nkq['nameid']);
                    $stmt->bindParam(':player', $player);
                    $stmt->bindParam(':gamescore', $score);
                    $stmt->bindParam(':date', $time);
                    $stmt->execute();
                } catch (PDOException $e) {
                    Core::showError($e->getMessage());
                }
            }
        }

        $stmt->nextRowset();
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_GameChamps_GetScoresbyPlayerandGame(:nameid, :player);');
            $stmt->bindParam(':nameid', $nameid);
            $stmt->bindParam(':player', $player);
            $stmt->execute();
            $gamesscore = $stmt->fetch();
            $rowcount = $stmt->rowCount();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
        if ($rowcount === 0) {
            $stmt->nextRowset();
            try {
                $stmt = mySQL::getConnection()->prepare('CALL sp_GameScore_InsertPlayerScore(:nameid, :player, :score, :ip, :date);');
                $stmt->bindParam(':nameid', $nameid);
                $stmt->bindParam(':player', $_SESSION['user']['id']);
                $stmt->bindParam(':score', $score);
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':date', $time);
                $stmt->execute();
                Core::loadRedirect(gettext('scoresaved'), $link);
            } catch (PDOException $e) {
                Core::showError($e->getMessage());
                die();
            }
        } else {
            $post = false;
            if ($sort == 'ASC') {
                if ($score < $gamesscore['score']) {
                    $post = true;
                } else {
                    Core::loadRedirect(gettext('scorewontsaved'), $link);
                }
            } elseif ($sort == 'DESC') {
                if ($score >= $gamesscore['score']) {
                    $post = true;
                } else {
                    Core::loadRedirect(gettext('scorewontsaved'), $link);
                }
            }
            if ($post == true) {
                try {
                    $stmt = mySQL::getConnection()->prepare('CALL sp_GameScore_UpdatePlayerScore(:nameid, :player, :score, :ip, :date);');
                    $stmt->bindParam(':nameid', $gamesscore['nameid']);
                    $stmt->bindParam(':player', $gamesscore['player']);
                    $stmt->bindParam(':score', $score);
                    $stmt->bindParam(':ip', $ip);
                    $stmt->bindParam(':date', $time);
                    $stmt->execute();
                } catch (PDOException $e) {
                    Core::showError($e->getMessage());
                }
                Core::loadRedirect(gettext('scoresaved'), $link);
            }
        }
    }
    private function __clone() {
    }
}