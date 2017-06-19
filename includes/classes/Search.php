<?php
declare(strict_types=1);
Core::stopDirectAccess();
class Search {
    protected $games;
    private function __construct() {}
	public static function searchGames($query) {
        /* Uses index */
        $time = Core::getCurrentDate();
        $sql = "SELECT `id`,`nameid`,`name`,`cat`,`desc` 
                    FROM `games`
					WHERE `active` = 'Yes' 
					AND `release_date` <= :releasedate 
					AND MATCH (`name`,`desc`,`instructions`,`keywords`)
					AGAINST (:searchterm WITH QUERY EXPANSION) LIMIT 51;";
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':releasedate', $time);
            $stmt->bindParam(':searchterm', $query, PDO::PARAM_STR);
            $stmt->execute();
            $searchresults = $stmt->fetchAll();
            $rowcount = $stmt->rowCount();
            $stmt->closeCursor();
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
        $dbconfig = Core::getDBConfig();
        return $dbconfig['google_search_ID'];
    }
    private function __clone() {}
}