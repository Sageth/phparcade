<?php
function scores_links() {
    Administrations::addLink(gettext('scores'), 'index.php?act=scores');
}
Administrations::addSubLink(gettext('configuration'), 'index.php?act=scores&mthd=config-form', 'scores');

function scores_admin($mthd) {
	$dbconfig = Core::getDBConfig();
	if ($mthd == 'config-form') {
		$checkedhsenable = ($dbconfig['highscoresenabled'] === 'on') ? 'checked' : "";
		$checkedguests = ($dbconfig['loginrequired'] === 'on') ? 'checked' : ""; ?>
		<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
			<div class="col-lg-5">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo gettext('general'); ?>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<?php echo Core::showGlyph('bullseye');?>
								<label for="highscoresenabled"><?php echo gettext('highscoresenabled'); ?></label>
								<div class="pull-right">
									<input type="checkbox" name="highscoresenabled" id="highscoresenabled" <?php echo $checkedhsenable;?> data-toggle="toggle"/>
								</div>
							</div>
						</div>
						<hr />
						<div class="row">
							<div class="col-md-12">
								<?php echo Core::showGlyph('child');?>
								<label for="loginrequired"><?php echo gettext('allowguestshighscore'); ?></label>
								<div class="pull-right">
									<input type="checkbox" name="loginrequired" id="loginrequired" <?php echo $checkedguests;?> data-toggle="toggle"/>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">&nbsp;</div>
				</div>
			</div>
			<input type='hidden' name='act' value='scores'/>
			<input type='hidden' name='mthd' value='config-do'/>
			<?php Pages::getSubmitButton();?>
		</form><?php
	} else if ($mthd == 'config-do') {
		Administrations::updateConfig('highscoresenabled', array_key_exists('highscoresenabled', $_POST) ? 'on' : 'off');
		Administrations::updateConfig('loginrequired', array_key_exists('loginrequired', $_POST) ? 'on' : 'off');
		Core::showSuccess(gettext('updatesuccess'));
	}
} ?>