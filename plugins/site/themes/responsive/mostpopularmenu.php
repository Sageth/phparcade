<?php
if(!isset($_SESSION)){session_start();}
$dbconfig = Core::getInstance()->getDBConfig(); ?>
<div class="mostpop_header_bg">&nbsp;</div>
<div class="mostpop_content"><?php
	$games = Games::getGames('all',6,10, '-all-',5);
	$games_rand = array_rand($games, 5);
	//$num = count($games_rand);
	$i = 0;
	foreach ($games_rand as $game => $gamevalue) {
		++$i;
		$link = Core::getLinkGame($games[$gamevalue]['id']);
		if (strlen($games[$gamevalue]['desc']) > 35) {
			$games[$gamevalue]['desc'] = substr($games[$gamevalue]['desc'], 0, 35) . ' ..';
		}
		if (strlen($games[$gamevalue]['name']) > 18) {
			$games[$gamevalue]['name'] = substr($games[$gamevalue]['name'], 0, 18);
		} ?>
		<div class='mostpopular_box'>
		<div class='mostpopular_box_left'>
			<a href="<?php echo $link; ?>"><?php
				$img = $dbconfig['imgurl'] . $games[$gamevalue]['nameid'] . '.png'; ?>
				<img class="img img-responsive img-rounded"
					 src="<?php echo($img); ?>"
				     alt="<?php echo $games[$gamevalue]['name']; ?>"
				     height="60px"
				     width="50px"
				     title="Play <?php echo $games[$gamevalue]['name']; ?> online for free!"/>
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
