<?php
if (!isset($_SESSION)) {
    session_start();
    $user = $_SESSION['user'];
}
$dbconfig = Core::getInstance()->getDBConfig();
global $params; ?>
<div class="col-lg-12">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h1 class="panel-title"><?php echo gettext('Error');?></h1>
		</div>
		<div class="panel-body"><?php
            Core::returnStatusCode(404);
            echo gettext('Error'); ?>
		</div>
	</div>
</div>
<script type="application/ld+json" defer>
    mixpanel.track(
        "Error Page"
    );
</script>