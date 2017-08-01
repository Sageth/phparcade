<?php
declare(strict_types=1);
Core::stopDirectAccess();

class Search {
    protected $games;
    private function __construct() {
    }
    public static function searchGames($time, $query, $limit) {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Games_SearchbyText(:releasedate, :searchterm, :glimit');
        $stmt->bindParam(':releasedate', $time);
        $stmt->bindParam(':searchterm', $query, PDO::PARAM_STR);
        $stmt->bindParam(':glimit', $limit);
        $stmt->execute();
        if ($query === "" || $stmt->rowCount() < 1) {
            die (gettext('nr'));
        }
        return $stmt->fetchAll();
    }
    public static function getGoogleSearchID() {
        $dbconfig = Core::getInstance()->getDBConfig();
        return $dbconfig['google_search_ID'];
    }
    private function __clone() {
    }
}