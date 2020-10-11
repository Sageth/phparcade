<?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = PHPArcade\Core::getDBConfig(); ?>
<div class="mostpop_header_bg">&nbsp;</div>
<div class="mostpop_content"><?php
    $games = PHPArcade\Games::getGames('all', 6, 10, 5, 'all');
    $games_rand = array_rand($games, 5);
    //$num = count($games_rand);
    $i = 0;
    foreach ($games_rand as $game => $gamevalue) {
        ++$i;
        $link = PHPArcade\Core::getLinkGame($games[$gamevalue]['id']);
        if (mb_strlen($games[$gamevalue]['desc']) > 35) {
            $games[$gamevalue]['desc'] = substr($games[$gamevalue]['desc'], 0, 35) . ' ..';
        }
        if (mb_strlen($games[$gamevalue]['name']) > 18) {
            $games[$gamevalue]['name'] = substr($games[$gamevalue]['name'], 0, 18);
        } ?>
		<div class='mostpopular_box'>
		<div class='mostpopular_box_left'>
			<a href="<?php echo $link; ?>"><?php
                $img = $dbconfig['imgurl'] . $games[$gamevalue]['nameid'] . EXT_IMG; ?>
				<img alt="<?php echo $games[$gamevalue]['name']; ?>"
                     class="img img-fluid rounded lazy"
                     data-src="<?php echo($img); ?>"
                     height="60px"
                     title="Play <?php echo $games[$gamevalue]['name']; ?> online for free!"
                     width="50px"
                />
			</a>
		</div>
		<div class='mostpopular_box_right'>
			<a href="<?php echo $link; ?>">
				<?php echo $games[$gamevalue]['name']; ?>
			</a><br/>
			<?php echo $games[$gamevalue]['desc']; ?>
			<br/>
			<a href="<?php echo $link; ?>"
               title="Play <?php echo $games[$gamevalue]['name']; ?> online for free!"
               class="playnow-66x18">
				<?php echo gettext('playnow'); ?>
			</a>
		</div>
		</div><?php
    } ?>
</div>
<div class="mostpop_btmcurve">&nbsp;</div>
