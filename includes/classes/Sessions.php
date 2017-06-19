<?php
declare(strict_types=1);
Core::stopDirectAccess();
class Sessions {
    protected $rowcount;
    private function __construct() {}
	public static function isOnline($id) {
        /* Uses index */
        $sql = 'SELECT * FROM `sessions` WHERE `userid` = :userid;';
        $stmt = mySQL::getConnection()->prepare($sql);
        $stmt->bindParam(':userid', $id);
        $stmt->execute();
        $rowcount = $stmt->rowCount();
        $stmt->closeCursor();
        return $rowcount == 1;
    }
    private function __clone() {}
}