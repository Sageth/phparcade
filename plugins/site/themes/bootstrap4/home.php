<!--Begin Content Below Carousel --><?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = Core::getInstance()->getDBConfig();
$i = 0;
foreach (Games::getGamesHomePage() as $game) {
    switch ($i){
        case 0:
            echo '<div class="card-deck mt-4">';
            break;
    } ?>
    <div class="card">
        <div class="card-body">
            <a href="<?php echo Core::getLinkGame($game['id']); ?>">
                <img class="img-thumbnail rounded mx-auto d-block"
                     data-original="<?php echo $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>"
                     alt="Play <?php echo $game['name']; ?> online for free!"
                     title="Play <?php echo $game['name']; ?> online for free!"
                     width="<?php echo $dbconfig['twidth']; ?>"
                     height="<?php echo $dbconfig['theight']; ?>"
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
                    <a href="<?php echo Core::getLinkGame($game['id']); ?>" class="btn btn-primary">
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
<!--suppress XmlDefaultAttributeValue -->
<script type="text/javascript" src="<?php echo JS_LAZYLOAD; ?>" integrity="<?php echo JS_LAZYLOAD_SRI;?>"
        crossorigin="anonymous" defer></script>
<!--suppress Annotator -->
<script>new LazyLoad();</script>
<!--End Content Section -->