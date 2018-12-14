<?php
namespace PHPArcade;

use PDO;
use PDOException;

class mySQL
{
    private static $db;
    private function __construct()
    {
        $inicfg = Core::getINIConfig();
        try {
            // assign PDO object to db variable
            self::$db = new PDO($inicfg['database']['driver'] . ':host=' . $inicfg['database']['host'] . ';port=' .
                                $inicfg['database']['port'] . ';dbname=' .
                                $inicfg['database']['schema'], $inicfg['database']['user'], $inicfg['database']['pass'], [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
            /* Enable logging of exceptions */
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /* Disable emulation of prepared statements, use REAL prepared statements instead. */
            self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            if ($inicfg['environment']['state'] === 'dev') {
                // Print error if you are not in production. Risks exposing database credentials in prod.
                echo 'Connection Error: ' . $e->getMessage();
            }
            die(gettext('syserror'));
        }
    }
    public static function getConnection()
    {
        if (!self::$db) {  //Guarantees single instance, if no connection object exists then create one.
            new mySQL();
        }
        return self::$db;
    }
}
