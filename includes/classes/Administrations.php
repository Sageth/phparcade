<?php
declare(strict_types=1);

namespace PHPArcade;

use PDOException;

{

    class Administrations
    {
        protected $prerequisites;

        private function __construct(){}

        public static function getPreReqs()
        {
            return [
            	'broken_games' => Games::getGamesBrokenCount() === 0 ? $broken = [CSS_SUCCESS, 'check'] : $broken = [CSS_DANGER, 'support'],
                'inactive_games' => Games::getGamesInactiveCount() === 0 ? $inactive = [CSS_SUCCESS, 'check'] : $inactive = ['warning', 'warning'],
                'ssl' => (self::getScheme() === 'https://') ? [CSS_SUCCESS, 'lock'] : [CSS_DANGER, 'unlock'],
                'folder_session' => is_writable(session_save_path()) ? [CSS_SUCCESS, 'file'] : [CSS_DANGER, 'pencil'],
            ];
        }

        public static function getProcessUser()
        {
            //http://php.net/manual/en/function.posix-getpwuid.php#82387
            if (function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
                return posix_getpwuid(posix_geteuid());
            }
            return getenv('USERNAME');
        }

        public static function getScheme()
        {
            return empty($_SERVER['HTTPS']) && (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) ? 'http://' : 'https://';
        }

        public static function isAdminArea()
        {
            global $adminarea;
            return isset($adminarea);
        }

        public static function updateConfig($key = "", $value = "")
        {
            try {
                $stmt = mySQL::getConnection()->prepare('CALL sp_Config_Update(:dbkey, :dbvalue);');
                $stmt->bindParam(':dbkey', $key);
                $stmt->bindParam(':dbvalue', $value);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                $warningtext = '<p>' . gettext('error') . ' ' . $e->getMessage() . '.</p>';
                $warningtext .= '<p>Update failed on Key = "' . $key . '" and Value = "' . $value . '"</p>';
                Core::showWarning($warningtext);
                return false;
            }
        }

        private function __clone(){}
    }


}