<?php
declare(strict_types=1);
Core::stopDirectAccess();
class Administrations
{
    protected $prerequisites;
    private function __construct()
    {
    }
    public static function addLink($linktext, $href)
    {
        global $links, $linkshref;
        $links[] = $linktext;
        $linkshref[] = $href;
    }
    public static function addSubLink($linktext, $href, $cat = 'none')
    {
        global $sublinks, $sublinkshref;
        $sublinks[$cat][] = $linktext;
        $sublinkshref[$cat][] = $href;
    }
    public static function getProcessUser()
    {
        return posix_getpwuid(posix_geteuid());
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
    public static function getPreReqs()
    {
        $scheme = self::getScheme();
        $inactiverowcount = Games::getGamesInactiveCount();
        $brokenrowcount = Games::getGamesBrokenCount();
        $prerequisites =
            ['broken_games' => $brokenrowcount === 0 ? $broken = ['green', 'check'] : $broken = ['red', 'support'],
             'inactive_games' => $inactiverowcount === 0 ? $inactive = ['green', 'check'] :
                 $inactive = ['yellow', 'warning'],
             'ssl' => ($scheme === 'https://') ? ['green', 'lock'] : ['red', 'unlock'],
             'folder_session' => is_writable(session_save_path()) ? ['green', 'file'] : ['red', 'pencil'],];
        return $prerequisites;
    }
    public static function getScheme()
    {
        $scheme = empty($_SERVER['HTTPS']) && (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) ? 'http://' : 'https://';
        return $scheme;
    }
    private function __clone()
    {
    }
}

function admin_set_content($c)
{
    global $content;
    /** @noinspection OnlyWritesOnParameterInspection */
    $content = $c;
    return "";
}
