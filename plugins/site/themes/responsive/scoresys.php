<?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = Core::getInstance()->getDBConfig();
$_GET['act'] = $_GET['act'] ?? '';
if ($_GET['act'] == 'Arcade' && $_GET['do'] == 'newscore') { //v2 games
    /* 'gname' comes from the submission in Flash and is equal to `game`.`nameid` */
    $game = Games::getGameByNameID($_POST['gname']);

    /* Get the game flags to determine scoring type */
    $sort = Scores::getScoreType('lowhighscore', $game['flags']) ? 'ASC' : 'DESC';

    /* Get the game link */
    $link = Core::getLinkGame($game['id']);
    if ($dbconfig['highscoresenabled'] === 'off') {
        Core::loadRedirect(gettext('highscoresoffline'), $link);
    }
    if (!$_SESSION) {
        Core::loadRedirect(gettext('logintosubmit'), $link);
    } else {
        if (isset($_POST['gname'], $_POST['gscore'])) {
            if ($_POST['gscore'] <= 0) {
                Core::loadRedirect(gettext('scoretoolow'), $link);
            }
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
            Scores::submitGameScore($game['id'], $_POST['gscore'], $_SESSION['user']['id'], $ip, $link, $sort);
        } else {
            Core::loadRedirect(gettext('errorsubmitscore'), $link);
        }
    }
}
