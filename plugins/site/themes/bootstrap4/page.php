<?php
/** @noinspection PhpUndefinedVariableInspection */
$page = PHPArcade\Pages::getPage($params[1]); ?>
<div class="row mt-4">
    <h1 class="page-header" itemprop="headline">
        <?php echo $page['title']; ?>
    </h1>
</div>
<div class="row mt-4">
    <div class="card-deck">
        <div class="card">
            <h3 class="card-header">
                <?php echo $page['title']; ?>
            </h3>
            <div class="card-body">
                <p class="card-text"><?php
                    echo PHPArcade\Core::encodeHTMLEntity($page['content'], ENT_QUOTES); ?>
                </p>
            </div>
        </div>
    </div>
</div>