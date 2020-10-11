<?php
if (!isset($_SESSION)) {
    session_start();
}
$games = PHPArcade\Games::getGames('all', 0, 5,-1, '-all-');
$i = 0; ?>
<!-- Carousel Section -->
<div class="carousel slide mt-4" data-ride="carousel" id="mainCarousel">
    <div class="carousel-inner col-lg-8 mx-auto"><?php
        foreach ($games as $game) {
            ++$i;
            $link = PHPArcade\Core::getLinkGame($game['id']);
            if ($i === 1) {
                echo '<div class="carousel-item active">';
            } else {
                echo '<div class="carousel-item">';
            } ?>
            <div class="card">
                <div class="card-header">
                    <h2>
                        <?php echo $game['name']; ?>
                    </h2>
                </div>
                <div class="card-body alert-dark text-center">
                    <div class="row">
                        <div class="col-1">&nbsp;</div>
                        <div class="col">
                            <a href="<?php echo $link; ?>">
                                <img alt="<?php $game['name']; ?>"
                                     class="rounded lazy"
                                     data-src="<?php echo IMG_URL . $game['nameid'] . EXT_IMG; ?>"
                                     height="<?php echo $dbconfig['theight']; ?>"
                                     width="<?php echo $dbconfig['twidth']; ?>"
                                />
                            </a>
                        </div>
                        <div class="col-1">&nbsp;</div>
                    </div>
                    <div class="row mt-2 d-none d-lg-block">
                        <div class="col">&nbsp;</div>
                        <div class="col">
                            <p class="text-justify text-black">
                                <?php echo $game['desc']; ?>
                            </p>
                        </div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
            </div>
            </div><?php
        } ?>
</div>
