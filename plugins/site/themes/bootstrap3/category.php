<?php
if (!isset($_SESSION)) {
    session_start();
}
global $params;
$dbconfig = PHPArcade\Core::getDBConfig();
$category = PHPArcade\Games::getCategory($params[1]);
$games = PHPArcade\Games::getGames($category['name'], 0, 10, $params[2], $dbconfig['gamesperpage']);
$i = 0; ?>
<div class="col-lg-12">
	<?php echo PHPArcade\Ads::getInstance()->showAds('Responsive'); ?>
	<div class="clearfix invisible"></div>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h1 class="panel-title"><?php echo $category['name'] . ' Games'; ?></h1>
		</div>
		<div class="panel-body"><?php
            /*
            // Advertising for specific categories
            switch ($category['name']) {
                case 'Strategy Games':
                    echo 'For the ultimate traditional game of chance, play <a href="http://www.example.com/" rel="nofollow">Free Games</a> online and enjoy multiplayer action and a variety of game options.';
                    break;
                case 'Word Games':
                    echo 'For the ultimate traditional game of chance, play <a href="http://www.example.com/" rel="nofollow">Free Games</a> online and enjoy multiplayer action and a variety of game options.';
                    break;
                default:
            }*/
            foreach ($games as $game) {
                $game['desc'] = mb_strlen($game['desc']) > 150 ? substr($game['desc'], 0, 150) . '...' : $game['desc'];
                $game['name'] = mb_strlen($game['name']) > 50 ? substr($game['name'], 0, 50) . '...' : $game['name'];
                $link = PHPArcade\Core::getLinkGame($game['id']); ?>
				<div class="col-md-4 col-md-4">
					<div class="thumbnail">
						<a href="<?php echo $link; ?>"><?php
                            $img = $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>
							<img class="img img-responsive img-rounded"
								 data-src="<?php echo $img; ?>"
							     alt="Play <?php echo $game['name']; ?> online for free!"
							     title="Play <?php echo $game['name']; ?> online for free!"
							     width="<?php echo $dbconfig['twidth']; ?>"
							     height="<?php echo $dbconfig['theight']; ?>"
								/>
						</a>
						<div class="caption">
							<h3><?php echo $game['name']; ?></h3>
							<p><?php echo strip_tags($game['desc']); ?></p>
							<p>
								<a href="<?php echo $link; ?>" class="btn btn-primary btn-lg btn-block">
									<?php echo gettext('playnow'); ?>
								</a>
							</p>
						</div>
					</div>
				</div><?php
                ++$i;
                if ($i == 3) {
                    ?>
					<div class="clearfix invisible"></div><?php
                    //Resets boxes
                    $i = 0;
                }
            } ?>
		</div>
		<div class="text-center">
			<ul class="pagination"><?php
                $pages = PHPArcade\Core::getPages($category['name']);
                for ($i = 0; $i < $pages; ++$i) {
                    ?>
					<li>
						<a href="<?php echo PHPArcade\Core::getLinkCategory($category['name'], $i + 1); ?>"
						   class="paginate_button" aria-controls="dataTables-example" tabindex="0">
							<?php echo $i + 1; ?>
						</a>
					</li><?php
                }?>
			</ul>
		</div>
	</div>
</div>

