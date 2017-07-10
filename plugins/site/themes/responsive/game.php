<?php if(!isset($_SESSION)){session_start();$user = $_SESSION['user'];}
Users::updateUserPlaycount();
global $params; ?>
<div itemscope itemtype="https://schema.org/Game"><?php
	$dbconfig = Core::getDBConfig();
    Core::doEvent('gamepage');
	$metadata = Core::getPageMetaData();
	$game = Games::getGame($params[1]);
	if (isset($game['id'])) {
		$img = $dbconfig['imgurl'] . $game['nameid'] . '.png';
		$thumbnailurl = $img;
		$origgamename = $game['name'];
		$epoch = $game['time'];
		$dt = new DateTime("@$epoch");
		$game['time'] = date('M d, Y', $game['time']);
		Games::updateGamePlaycount($game['id']); ?>
		<div class="col-lg-12">
			<h1 class="page-header" itemprop="name"><?php echo $game['name']; ?></h1>
		</div>
		<div class="col-lg-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h2 class="panel-title"><?php echo gettext('description'); ?></h2>
				</div>
				<div class="panel-body">
					<p class="text-info" itemprop="description"><?php echo $game['desc']; ?></p>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h2 class="panel-title"><?php echo gettext('instructions'); ?></h2>
				</div>
				<div class="panel-body">
					<p class="text-info"><?php echo $game['instructions']; ?></p>
				</div>
			</div>
		</div><?php
		// Fix for bad champions
		/* 	This next section corrects the "Games Champs Table.  When you delete the games_champs users that
			don't exist anymore, it causes the "champ" to actually be the next person that plays... not the
			actual champ. This code patches that issue by taking the best score for that game and updating the
			champs table. This code is driven by above if ($scoreslist->getScoreType("lowhighscore", $game['flags'])).
			Also, the champs table had a PK added on nameid to prevent duplicates.  I'm not sure that $scores needs to
			be defined, but I'm not going to change it just yet.
			$info[0] is the score in the games_champs table
			$tscore['x'] is the top player in the games list.*/
		if (Scores::getScoreType('lowhighscore', $game['flags'])) {
			$scores = Scores::getGameScore($game['id'], 'ASC', TOP_SCORE_COUNT);
			$tscores = Scores::getGameScore($game['id'], 'ASC', 1); // Fix scores when users are deleted
		} else {
			$scores = Scores::getGameScore($game['id'], 'DESC', TOP_SCORE_COUNT);
			$tscores = Scores::getGameScore($game['id'], 'DESC',1); //Fix champ scores when users are deleted
		}
		foreach ($tscores as $tscore) { //Get the top score on that game.
			$tnameid = $tscore['nameid'];
			$tuser = $tscore['player'];
			$tscore = $tscore['score'];
			$info[0] = $info ?? '';
			if ($tscore <> $info[0]) {
				//Compare the top score to the champ ($info) which is defined in scoreinfo.php
				// If the scores don't match, then correct it.
				Games::updateGameChamp($tnameid,$tuser,$tscore,Core::getCurrentDate(),$game['id']);
			}
		}
		/* End Games Champs Fix */ ?>
		<!-- Game Code -->
		<div class="clearfix invisible"></div>
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $game['name']; ?></h3>
				</div>
				<div class="panel-body text-center">
					<?php echo Ads::getInstance()->showAds('Responsive'); ?>
					<div class="clearfix invisible">&nbsp;</div><?php
					$game['type'] = $game['type'] ?? '';
					switch ($game['customcode']) {
						case null:
						case '':
							/** @noinspection MissingOrEmptyGroupStatementInspection */
                            if ($game['type'] === 'extlink') {
                            } else {
                                echo $game['code'];
                                Core::doEvent('gameplay');
                            }
							break;
						default:
							echo $game['customcode'];
					} ?>
					<div class="clearfix invisible">&nbsp;</div>
					<?php echo Ads::getInstance()->showAds('Responsive'); ?>
					<div class="clearfix invisible">&nbsp;</div>
				</div>
			</div>
		</div>
		<div class="clearfix invisible"></div><?php
		if ($game['flags'] <> '') {  /* If there are flags set (i.e. NOT a Mochi game), then show the score table*/
			$i = 0; ?>
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title"><?php echo gettext('top10score'); ?></h2>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="dataTables-example">
								<thead>
									<tr>
										<th><?php echo gettext('Ranking');?></th>
										<th><?php echo gettext('player'); ?></th>
										<th><?php echo gettext('score'); ?></th>
									<th><?php echo gettext('date'); ?></th>
								</tr>
								</thead>
								<tbody><?php
									if (count($scores) != 0) {
										foreach ($scores as $score) {
											++$i;
											$d_score = date('m/d/Y', $score['date']);
											$user = Users::getUserbyID($score['player']);
                                            $avatar = $user['avatarurl'] === '' ? SITE_URL .
                                                                                  'includes/images/noav.png' : SITE_URL .
                                                                                                               $user['avatarurl']; ?>
											<tr class="odd gradeA">
												<td><?php echo $i; ?></td>
												<td>
                                                    <img src="<?php echo $avatar;?>" class="img img-responsive img-circle" style="float:left" height="30" width="30" />
                                                    &nbsp;
                                                    <a href="<?php echo Core::getLinkProfile($user['id']); ?>">
                                                        <?php echo $user['username']; ?>
                                                    </a>
                                                </td>
												<td><?php echo Scores::formatScore($score['score']); ?></td>
												<td><?php echo $d_score; ?></td>
											</tr><?php
										}
									} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- End Game Code --><?php
		}
		if ($dbconfig['disqus_on'] === 'on') { ?>
            <div class="clearfix invisible" itemprop="comment"></div>
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo gettext('disqus'); ?></h3>
					</div>
					<div class="panel-body">
						<?php require_once INST_DIR . 'includes/js/Disqus/disqus.php';?>
					</div>
				</div>
			</div><?php
		}
	} else { ?>
		<h1><?php echo gettext('404status'); ?></h1>
		<h2><?php echo gettext('404page'); ?></h2><?php
		Core::returnStatusCode(404);
		die();
	} ?>
	<!-- Related Items -->
	<div class="clearfix invisible"></div>
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo gettext('additionalgames');?></h3>
            </div>
            <div class="panel-body text-center"><?php
                $gameslikethis = Games::getGamesLikeThis();
                foreach ($gameslikethis as $gamelikethis) {
                    $link = Core::getLinkGame($gamelikethis['id']); ?>
                    <div class="col-md-3 col-md-4">
                    <div class="thumbnail">
                        <a href="<?php echo $link; ?>"><?php
                            $img = $dbconfig['imgurl'] . $gamelikethis['nameid'] . '.png'; ?>
                            <img class="img img-responsive img-rounded"
                                 src="<?php echo $img; ?>"
                                 alt="Play <?php echo $gamelikethis['name']; ?> online for free!"
                                 title="Play <?php echo $gamelikethis['name']; ?> online for free!"
                                 width="<?php echo $dbconfig['twidth']; ?>"
                                 height="<?php echo $dbconfig['theight']; ?>"
                            />
                        </a>
                        <div class="caption">
                            <div class="caption">
                                <h3><?php echo $gamelikethis['name']; ?></h3>
                                <p><?php echo $gamelikethis['desc']; ?></p>
                                <p>
                                    <a href="<?php echo $link; ?>" class="btn btn-primary" role="button">
                                        <?php echo gettext('playnow'); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    </div><?php
                }
                unset($gameslikethis);?>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title"><?php echo gettext('relatedproducts'); ?></h2>
            </div>
            <div class="panel-body">
                <script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=b6978aaa-8da0-4748-bfab-fb4d3d78a04d&storeId=freonlflaga07-20"></script>
            </div>
        </div>
    </div>
	<!-- End Related Items -->
    <div class="col-lg-12">
        <div class="panel panel-default">
		    <div class="panel-heading"><?php echo gettext('gamemetadata');?></div>
		    <div class="panel-body">
                <p itemprop="keywords"><?php echo $game['keywords']; ?></p>
                <p itemprop="datePublished"><?php echo $dt->format('Y-m-d H:i:s'); ?></p>
                <p itemprop="thumbnailUrl"><?php echo $thumbnailurl; ?></p>
                <p itemprop="image"><?php echo $thumbnailurl; ?></p>
            </div>
        </div>
    </div>
</div>