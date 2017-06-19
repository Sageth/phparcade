<?php
/* Loads classes */
require_once $_SERVER['DOCUMENT_ROOT'] . '/cfg.php';


try {
    $sql = 'UPDATE `categories` SET `order` = :categoryorder WHERE `id` = :categoryid;';
    $stmt = mySQL::getConnection()->prepare($sql);

    /* For each id named rowsort, get the order and ID */
    foreach ($_POST['rowsort'] as $order => $id) {
        $stmt->bindParam(':categoryorder', $order);
        $stmt->bindParam(':categoryid', $id);
        $stmt->execute();
    }

    $stmt->closeCursor();
} catch (PDOException $e) {
    Core::showError($e->getMessage());
}
