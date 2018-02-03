<?php
/** @noinspection PhpUndefinedVariableInspection */
$page = Pages::getPage($params[1]); ?>
<div class="col-lg-12">
	<div class="card card text-white bg-info">
		<div class="card-header">
			<h1 class="card-title"><?php echo $page['title']; ?></h1>
		</div>
		<div class="card-body"><?php
            echo Core::encodeHTMLEntity($page['content'], ENT_QUOTES); ?>
		</div>
	</div>
</div>
