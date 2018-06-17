<?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = PHPArcade\Core::getDBConfig();
$_GET['act'] = $_GET['act'] ?? '';
if ($_GET['act'] == 'Arcade' && $_GET['do'] == 'newscore') { //v2 games
    /* 'gname' comes from the submission in Flash and is equal to `game`.`nameid` */
    $game = PHPArcade\Games::getGameByNameID($_POST['gname']);

    /* Get the game flags to determine scoring type */
    $sort = PHPArcade\Scores::getScoreType('lowhighscore', $game['flags']) ? 'ASC' : 'DESC';

    /* Get the game link */
    $link = PHPArcade\Core::getLinkGame($game['id']);

    if (!$_SESSION) {
        PHPArcade\Core::loadRedirect($link);
    } else {
        if (isset($_POST['gname'], $_POST['gscore'])) {
            if ($_POST['gscore'] <= 0) {
                PHPArcade\Core::loadRedirect($link);
            }
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
            PHPArcade\Scores::submitGameScore($game['id'], $_POST['gscore'], $_SESSION['user']['id'], $ip, $link, $sort);
        } else {
            PHPArcade\Core::loadRedirect($link);
        }
    }
}
