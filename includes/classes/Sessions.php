<?php
declare(strict_types=1);
namespace PHPArcade;

class Sessions
{
    protected $stmt;
    private function __construct()
    {
    }
    public static function isOnline($id)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Sessions_GetSessionbyUserid(:userid);');
        $stmt->bindParam(':userid', $id);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }
    private function __clone()
    {
    }
}
