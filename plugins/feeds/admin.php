<?php
function feeds_links() {
    Administrations::addLink('Feeds', 'index.php?act=feeds');
}

Administrations::addSubLink(gettext('configuration'), 'index.php?act=feeds&mthd=config', 'feeds');
/**
 * @param $mthd
 */
function feeds_admin($mthd) {
	$dbconfig = Core::getDBConfig();
	switch($mthd){
		case "":
		case 'config':
			$checked = ($dbconfig['rssenabled'] === 'on') ? 'checked' : "";?>
			<!--suppress HtmlFormInputWithoutLabel -->
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('rssfeeds'); ?>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<?php echo Core::showGlyph('rss');?>
									<label><?php echo gettext('enablerss'); ?></label>
									<div class="checkbox-inline pull-right">
										<input type="checkbox" name="rssenabled" id="rssenabled" <?php echo $checked;?> data-toggle="toggle" />
									</div>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="col-md-12">
									<div class="form-group form-inline">
										<?php echo Core::showGlyph('asterisk');?>
										<label><?php echo gettext('numlatest'); ?></label>
										<input class="form-control" type='text' name='rssnumlatest'
										       value='<?php echo $dbconfig['rssnumlatest']; ?>'/>
									</div>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<?php echo Core::showGlyph('link');?>
										<label><?php echo gettext('rssfeedurl'); ?></label>
										<input class="form-control" type='text' name='rssfeed'
										       value='<?php echo $dbconfig['rssfeed']; ?>'/>
										<p class="help-block"><?php echo gettext('rssfeedexample1'); ?></p>
										<p class="help-block"><?php echo gettext('rssfeedexample2'); ?></p>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='feeds'/>
				<input type='hidden' name='mthd' value='config-do'/><?php
				Pages::getSubmitButton(); ?>
			</form><?php
			break;
		case 'config-do':
			Administrations::updateConfig('rssnumlatest', $_POST['rssnumlatest']);
			Administrations::updateConfig('rssenabled', array_key_exists('rssenabled', $_POST) ? 'on' : 'off');
			Administrations::updateConfig('rssfeed', $_POST['rssfeed']);
			Core::showSuccess(gettext('updatesuccess'));
			break;
		default:
	}
}