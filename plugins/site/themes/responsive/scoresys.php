<?php
if(!isset($_SESSION)){session_start();}
$dbconfig = Core::getDBConfig();
$_GET['act'] = $_GET['act'] ?? '';
if ($_GET['act'] == 'Arcade' && $_GET['do'] == 'newscore') { //v2 games
	$info = Games::getGameByNameID($_POST['gname']);
	$sort = Scores::getScoreType('lowhighscore', $info['flags']) ? 'ASC' : 'DESC';
	$link = Core::getLinkGame($info['id']);
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
			Scores::submitGameScore($info[0], $_POST['gscore'], $_SESSION['user']['id'], $ip, $link, $sort);
		} else {
			Core::loadRedirect(gettext('errorsubmitscore'), $link);
		}
	}
}