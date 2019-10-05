<!--Begin Content Below Carousel --><?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = PHPArcade\Core::getDBConfig();
$i = 0;
foreach (PHPArcade\Games::getGamesHomePage() as $game) {
    switch ($i) {
        case 0:
            echo '<div class="card-deck mt-4">';
            break;
        default:
    } ?>
    <div class="card">
        <div class="card-body">
            <a href="<?php echo PHPArcade\Core::getLinkGame($game['id']); ?>">
                <img alt="Play <?php echo $game['name']; ?> online for free!"
                     class="img-thumbnail rounded mx-auto d-block"
                     data-src="<?php echo $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>"
                     height="<?php echo $dbconfig['theight']; ?>"
                     title="Play <?php echo $game['name']; ?> online for free!"
                     width="<?php echo $dbconfig['twidth']; ?>"
                />
            </a>
            <div class="card-body">
                <h1 class="home-game-title text-center">
                    <?php echo $game['name']; ?>
                </h1>
                <p class="card-text">
                    <?php echo $game['desc']; ?>
                </p>
                <p class="text-center">
                    <a class="btn btn-primary" href="<?php echo PHPArcade\Core::getLinkGame($game['id']); ?>">
                        <?php echo gettext('playnow'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div><?php
    switch ($i) {
        case 3:
            echo "</div>";
            $i = 0;
            break;
        default:
            ++$i;
    }
}?>
<!--End Content Section -->