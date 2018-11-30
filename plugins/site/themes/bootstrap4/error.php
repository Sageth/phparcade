<?php
//TODO: Determine if this is still used. I don't think it is

if (!isset($_SESSION)) {
    session_start();
    $user = $_SESSION['user'];
}
$dbconfig = PHPArcade\Core::getDBConfig();
global $params; ?>
<div class="col-lg-12">
	<div class="card card text-white bg-info">
		<div class="card-header">
			<h1 class="card-title">
                <?php echo gettext('Error');?>
            </h1>
		</div>
		<div class="card-body"><?php
            PHPArcade\Core::returnStatusCode(404);
            echo gettext('Error'); ?>!
		</div>
	</div>
</div>
<?php if (!empty($dbconfig['mixpanel_id'])) {
                ?>
    <script async>
        mixpanel.track("Error Page");
    </script><?php
            }
?>