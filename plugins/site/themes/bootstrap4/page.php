<?php
/** @noinspection PhpUndefinedVariableInspection */
$page = Pages::getPage($params[1]); ?>
<div class="row">
    <h1 class="page-header" itemprop="headline">
        <?php echo $page['title']; ?>
    </h1>
    <div class="card-deck mt-4">
        <div class="card">
            <h3 class="card-header">
                <?php echo $page['title']; ?>
            </h3>
            <div class="card-body">
                <p class="card-text"><?php
                    echo Core::encodeHTMLEntity($page['content'], ENT_QUOTES); ?>
                </p>
            </div>
        </div>
    </div>
</div>