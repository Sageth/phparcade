<?php
declare(strict_types=1);
namespace PHPArcade;

class Ads
{
    private static $instance;
    protected $ad;
    private function __construct()
    {
    }
    public static function getInstance()
    {
        /* Singleton use */
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public static function getAd($id)
    {
        /* Used by admin to show the advertisement code, edit location, etc. */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_GetAdbyID(:adid);');
        $stmt->bindParam(P_ADID, $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public static function getAds()
    {
        /* Used in admin to show the list of ads in a table */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_GetAllbyName();');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function deleteAd($id)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_Delete_ID(:adid);');
        $stmt->bindParam(P_ADID, $id);
        $stmt->execute();
        Core::showSuccess(gettext('deletesuccess'));
    }
    public static function insertAd($id = null, $name, $code, $location, $advertisername, $comment)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Ads_Insert(:adid, :adname, :adcode, :adlocation, :advertiser, :adcomments)');
        $stmt->bindParam(P_ADID, $id);
        $stmt->bindParam(':adname', $name);
        $stmt->bindParam(':adcode', $code);
        $stmt->bindParam(':adlocation', $location);
        $stmt->bindParam(':advertiser', $advertisername);
        $stmt->bindParam(':adcomments', $comment);
        $stmt->execute();
        Core::showSuccess(gettext('addsuccess'));
    }
    public static function showAds()
    {
        /* Displays ad on the front-end webpage */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_GetAll_Random();');
        $stmt->execute();
        $ad = $stmt->fetch();
        return $ad['code'];
    }
    public static function updateAd($id, $name, $code, $location, $advertisername, $comment)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Ads_Update(:adid, :adname, :adcode, :adlocation, :advertiser, :adcomments)');
        $stmt->bindParam(P_ADID, $id);
        $stmt->bindParam(':adname', $name);
        $stmt->bindParam(':adcode', $code);
        $stmt->bindParam(':adlocation', $location);
        $stmt->bindParam(':advertiser', $advertisername);
        $stmt->bindParam(':adcomments', $comment);
        $stmt->execute();
        Core::showSuccess(gettext('updatesuccess'));
    }
    private function __clone()
    {
    }
}
