<?php
declare(strict_types=1);
Core::stopDirectAccess();
class Search {
    protected $games;
    private function __construct() {}
	public static function searchGames($time, $query, $limit) {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Games_SearchbyText(:releasedate, :searchterm, :glimit');
            $stmt->bindParam(':releasedate', $time);
            $stmt->bindParam(':searchterm', $query, PDO::PARAM_STR);
            $stmt->bindParam(':glimit', $limit);
            $stmt->execute();
            $searchresults = $stmt->fetchAll();
            $rowcount = $stmt->rowCount();
            if ($query === "" || $rowcount < 1) {
                die (gettext('nr'));
            }
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        unset($rowcount);
        /** @noinspection PhpUndefinedVariableInspection */
        return $searchresults;
    }
	public static function getGoogleSearchID() {
        $dbconfig = Core::getInstance()->getDBConfig();
        return $dbconfig['google_search_ID'];
    }
    private function __clone() {}
}