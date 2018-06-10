<?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = PHPArcade\Core::getDBConfig(); ?>
<div class="col-lg-12">
    <div class="card card text-white bg-info">
        <div class="card-header">
            <h1 class="card-title">
                <?php echo $dbconfig['sitetitle']; ?>
            </h1>
        </div>
        <div class="card-body"><?php
            $games = PHPArcade\Search::searchGames(PHPArcade\Core::getCurrentDate(), $_GET['q'], 51);
            $i = 0;
            foreach ($games as $game) {
                $link = PHPArcade\Core::getLinkGame($game['id']);
                $game['desc'] = mb_strlen($game['desc']) > 120 ? substr($game['desc'], 0, 120) . '...' : $game['desc'];
                $game['name'] = mb_strlen($game['name']) > 50 ? substr($game['name'], 0, 50) . '...' : $game['name']; ?>
                <div class="col-md-4 col-md-4">
                    <div class="card card-body">
                        <a href="<?php echo $link; ?>"><?php
                            $img = $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>
                            <img alt="Play <?php echo $game['name']; ?> online for free!"
                                 class="img img-fluid rounded"
                                 data-src="<?php echo $img; ?>"
                                 height="<?php echo $dbconfig['theight']; ?>"
                                 title="Play <?php echo $game['name']; ?> online for free!"
                                 width="<?php echo $dbconfig['twidth']; ?>"
                            />
                        </a>
                        <div class="caption">
                            <h3><?php echo $game['name']; ?></h3>
                            <p><?php echo $game['desc']; ?></p>
                            <p>
                                <a href="<?php echo $link; ?>" class="btn btn-primary btn-lg btn-block">
                                    <?php echo gettext('playnow'); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div><?php
                ++$i;
                if ($i === 3) {
                    ?>
                    <div class="clearfix invisible"></div><?php //Resets boxes
                    $i = 0;
                }
            } ?>
        </div>
    </div>
</div>