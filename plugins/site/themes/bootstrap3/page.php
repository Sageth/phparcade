<?php
/** @noinspection PhpUndefinedVariableInspection */
$page = PHPArcade\Pages::getPage($params[1]); ?>
<div class="col-lg-12">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h1 class="panel-title"><?php echo $page['title']; ?></h1>
		</div>
		<div class="panel-body"><?php
            echo PHPArcade\Core::encodeHTMLEntity($page['content'], ENT_QUOTES); ?>
		</div>
	</div>
</div>
