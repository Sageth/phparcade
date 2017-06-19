<?php
declare(strict_types=1);
Core::stopDirectAccess();

class Ads {
    private static $instance;
    protected      $ad;
    private function __construct() {
    }
    public static function getInstance() {
        /* Singleton use */
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public static function getAd($id) {
        /* Used by admin to show the advertisement code, edit location, etc. */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_GetAdbyID(:adid);');
        $stmt->bindParam(':adid', $id);
        $stmt->execute();
        $ad = $stmt->fetch();
        return $ad;
    }
    public static function getAds() {
        /* Used in admin to show the list of ads in a table */
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_GetAllbyName();');
            $stmt->execute();
            $ads = $stmt->fetchAll();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
        return $ads;
    }
    public static function deleteAd($id) {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_Delete_ID(:adid);');
            $stmt->bindParam(':adid', $id);
            $stmt->execute();
            Core::showSuccess(gettext('deletesuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
            die();
        }
    }
    public static function insertAd($id = null, $name, $code, $location, $advertisername, $comment) {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_Insert(:adid, :adname, :adcode, :adlocation, :advertiser, :adcomments)');
            $stmt->bindParam(':adid', $id);
            $stmt->bindParam(':adname', $name);
            $stmt->bindParam(':adcode', $code);
            $stmt->bindParam(':adlocation', $location);
            $stmt->bindParam(':advertiser', $advertisername);
            $stmt->bindParam(':adcomments', $comment);
            $stmt->execute();
            Core::showSuccess(gettext('addsuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    public static function showAds() {
        /* Displays ad on the front-end webpage */
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Ads_GetAll_Random();');
            $stmt->execute();
            $ad = $stmt->fetch();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $ad['code'];
    }
    public static function updateAd($id, $name, $code, $location, $advertisername, $comment) {
        try {
            $stmt =
                mySQL::getConnection()->prepare('CALL sp_Ads_Update(:adid, :adname, :adcode, :adlocation, :advertiser, :adcomments)');
            $stmt->bindParam(':adid', $id);
            $stmt->bindParam(':adname', $name);
            $stmt->bindParam(':adcode', $code);
            $stmt->bindParam(':adlocation', $location);
            $stmt->bindParam(':advertiser', $advertisername);
            $stmt->bindParam(':adcomments', $comment);
            $stmt->execute();
            Core::showSuccess(gettext('updatesuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    private function __clone() {
    }
}