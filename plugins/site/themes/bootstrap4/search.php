<?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = Core::getInstance()->getDBConfig(); ?>
<div class="col-lg-12">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h1 class="panel-title">
                <?php echo $dbconfig['sitetitle']; ?>
            </h1>
        </div>
        <div class="panel-body"><?php
            $games = Search::searchGames(Core::getCurrentDate(), $_GET['q'], 51);
            $i = 0;
            foreach ($games as $game) {
                $link = Core::getLinkGame($game['id']);
                $game['desc'] = mb_strlen($game['desc']) > 120 ? substr($game['desc'], 0, 120) . '...' : $game['desc'];
                $game['name'] = mb_strlen($game['name']) > 50 ? substr($game['name'], 0, 50) . '...' : $game['name']; ?>
                <div class="col-md-4 col-md-4">
                    <div class="thumbnail">
                        <a href="<?php echo $link; ?>"><?php
                            $img = $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>
                            <img class="img img-responsive img-rounded"
                                 src="<?php echo $img; ?>"
                                 alt="Play <?php echo $game['name']; ?> online for free!"
                                 title="Play <?php echo $game['name']; ?> online for free!"
                                 width="<?php echo $dbconfig['twidth']; ?>"
                                 height="<?php echo $dbconfig['theight']; ?>"
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
                if ($i == 3) {
                    ?>
                    <div class="clearfix invisible"></div><?php //Resets boxes
                    $i = 0;
                }
            } ?>
        </div>
    </div>
</div>