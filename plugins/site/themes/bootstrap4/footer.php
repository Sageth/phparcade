<?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = Core::getInstance()->getDBConfig(); ?>
<!-- Begin Footer -->
<div class="footer navbar-static-bottom mt-4">
	<div class="card-footer container-fluid text-center"><?php
            // It would be greatly appreciated if you didn't remove the footer link to the project
            // Please feel free to customize as much as you'd like?>
		<a href="<?php echo Core::getLinkPage(4); ?>" title="<?php echo gettext('aboutus'); ?>">
			<?php echo Core::showGlyph('bullhorn');?> <?php echo gettext('aboutus'); ?>
		</a> |
		<a href="<?php echo Core::getLinkPage(5); ?>" title="<?php echo gettext('contactus'); ?>">
			<?php echo Core::showGlyph('envelope');?> <?php echo gettext('contactus'); ?>
		</a> |
		<a href="<?php echo Core::getLinkPage(1); ?>" title="<?php echo gettext('terms'); ?>">
			<?php echo Core::showGlyph('pencil');?> <?php echo gettext('terms'); ?>
		</a> |
		<a href="<?php echo Core::getLinkPage(2); ?>" title="<?php echo gettext('pp'); ?>">
			<?php echo Core::showGlyph('user-secret');?> <?php echo gettext('pp'); ?>
		</a> |
		<a href="<?php echo $dbconfig['rssfeed']; ?>" title="<?php echo gettext('rssfeeds'); ?>">
			<?php echo Core::showGlyph('rss');?> <?php echo gettext('rssfeeds'); ?>
		</a> |
		<a href="<?php echo URL_GITHUB_PHPARCADE;?>" title="<?php echo gettext('disclaimer'); ?>">
			<?php echo Core::showGlyph('download');?> <?php echo gettext('disclaimer'); ?>
		</a>
	</div>
</div>
<!-- End Footer -->