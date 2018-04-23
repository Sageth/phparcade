<?php
if (!isset($_SESSION)) {
    session_start();
}
global $params;
$dbconfig = PHPArcade\Core::getDBConfig();
$category = PHPArcade\Games::getCategory($params[1]);
$games = PHPArcade\Games::getGames($category['name'], 0, 10, $params[2], $dbconfig['gamesperpage']); ?>
<div class="row">
    <?php echo PHPArcade\Ads::getInstance()->showAds('Responsive'); ?>
    <h1><?php echo $category['name'] . ' Games'; ?></h1>
    <div class="row"><?php
        foreach ($games as $game) {
            $game['desc'] = mb_strlen($game['desc']) > 150 ? substr($game['desc'], 0, 150) . '...' : $game['desc'];
            $game['name'] = mb_strlen($game['name']) > 50 ? substr($game['name'], 0, 50) . '...' : $game['name'];
            $link = PHPArcade\Core::getLinkGame($game['id']);?>
            <div class="card col-md-4 mt-4">
                <div class="card-body">
                    <a href="<?php echo $link; ?>"><?php
                        $img = $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>
                        <img class="img-thumbnail rounded mx-auto d-block"
                             data-src="<?php echo $img; ?>"
                             alt="Play <?php echo $game['name']; ?> online for free!"
                             title="Play <?php echo $game['name']; ?> online for free!"
                             width="<?php echo $dbconfig['twidth']; ?>"
                             height="<?php echo $dbconfig['theight']; ?>"
                        />
                    </a>
                    <h3 class="card-title">
                        <?php echo $game['name']; ?>
                    </h3>
                    <p class="card-text">
                        <?php echo strip_tags($game['desc']);?>
                    </p>
                    <p class="card-text text-center">
                        <a href="<?php echo $link; ?>" class="btn btn-primary">
                            <?php echo gettext('playnow'); ?>
                        </a>
                    </p>
                </div>
            </div>
            <?php
        } ?>
    </div>
    <nav aria-label="Category Pagination">
        <ul class="pagination pagination-sm justify-content-center flex-wrap mt-4"><?php

            /* TODO: Fix this so it doesn't need flex-wrap */
            $totalPages = PHPArcade\Core::getPages($category['name']);
            for ($i = 0; $i < $totalPages; ++$i) {
                /* If $i is equal to $params[2] minus 1, then that's the active page */ ?>
                <li class="page-item <?php if ($i === $params[2]-1) { echo 'active'; };?>">
                    <a class="page-link" href="<?php echo PHPArcade\Core::getLinkCategory($category['name'], $i + 1); ?>">
                        <?php echo $i + 1; ?>
                    </a>
                </li><?php
            }?>
        </ul>
    </nav>
</div>

<!--suppress XmlDefaultAttributeValue -->
<script type="text/javascript" src="<?php echo JS_LAZYLOAD; ?>" integrity="<?php echo JS_LAZYLOAD_SRI;?>"
        crossorigin="anonymous" defer></script>
<!--suppress Annotator -->
<script>var myLazyLoad = new LazyLoad();</script>
