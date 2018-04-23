<?php
/* Loads classes */
require_once $_SERVER['DOCUMENT_ROOT'] . '/cfg.php';
try {
    $stmt = PHPArcade\mySQL::getConnection()->prepare('CALL sp_Categories_UpdateOrder(:categoryorder, :categoryid)');

    /* For each id named rowsort, get the order and ID */
    foreach ($_POST['rowsort'] as $order => $id) {
        $stmt->bindParam(':categoryorder', $order);
        $stmt->bindParam(':categoryid', $id);
        $stmt->execute();
    }
} catch (PDOException $e) {
    PHPArcade\Core::showError($e->getMessage());
}
