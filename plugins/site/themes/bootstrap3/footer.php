<?php
if (!isset($_SESSION)) {
    session_start();
}
$dbconfig = PHPArcade\Core::getDBConfig(); ?>
<!-- Begin Footer -->
<div class="footer navbar-static-bottom">
	<div class="panel-footer container-fluid text-center"><?php
            // It would be greatly appreciated if you didn't remove the footer link to the project
            // Please feel free to customize as much as you'd like?>
		<a href="<?php echo PHPArcade\Core::getLinkPage(4); ?>" title="<?php echo gettext('aboutus'); ?>">
			<?php echo PHPArcade\Core::showGlyph('bullhorn');?> <?php echo gettext('aboutus'); ?>
		</a> |
		<a href="<?php echo PHPArcade\Core::getLinkPage(5); ?>" title="<?php echo gettext('contactus'); ?>">
			<?php echo PHPArcade\Core::showGlyph('envelope');?> <?php echo gettext('contactus'); ?>
		</a> |
		<a href="<?php echo PHPArcade\Core::getLinkPage(1); ?>" title="<?php echo gettext('terms'); ?>">
			<?php echo PHPArcade\Core::showGlyph('pencil-alt');?> <?php echo gettext('terms'); ?>
		</a> |
		<a href="<?php echo PHPArcade\Core::getLinkPage(2); ?>" title="<?php echo gettext('pp'); ?>">
			<?php echo PHPArcade\Core::showGlyph('user-secret');?> <?php echo gettext('pp'); ?>
		</a> |
		<a href="<?php echo $dbconfig['rssfeed']; ?>" title="<?php echo gettext('rssfeeds'); ?>">
			<?php echo PHPArcade\Core::showGlyph('rss');?> <?php echo gettext('rssfeeds'); ?>
		</a> |
		<a href="<?php echo URL_GITHUB_PHPARCADE;?>" title="<?php echo gettext('disclaimer'); ?>">
			<?php echo PHPArcade\Core::showGlyph('download');?> <?php echo gettext('disclaimer'); ?>
		</a>
	</div>
</div>
<!-- End Footer -->