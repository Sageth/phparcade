<?php
function ads_links() {
	Administrations::addLink(gettext('ads'), 'index.php?act=ads');
}

Administrations::addSubLink(gettext('add'), 'index.php?act=ads&mthd=addad-form', 'ads');
Administrations::addSubLink(gettext('ads-manage'), 'index.php?act=ads&mthd=manage', 'ads');


/**
 * @param $mthd
 */
function ads_admin($mthd) {
	switch($mthd) {
		case 'addad-form': ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('advertisement') ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('name'); ?></label>
								<input class="form-control" title="Name" type="text" name="name"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('adcode'); ?></label>
								<textarea class="form-control" title="Code" name='code' cols='60' rows='10'></textarea>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('optionalinfo'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('location'); ?></label>
								<input class="form-control" title="Location" type='text' name='location'/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('advertiser'); ?></label>
								<input class="form-control" title="Advertiser Name" type='text' name='advertisername'/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('comments'); ?></label>
								<textarea class="form-control" title="Comment" name="adcomment" rows="3"></textarea>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='ads'/>
				<input type='hidden' name='mthd' value='addad-do'/>
				<?php Pages::getSubmitButton(); ?>
			</form><?php
			break;
		case 'addad-do':
			if ($_POST['name'] == "" || $_POST['code'] == "") {
				Core::showWarning(gettext('fillallerror'));
			} else {
				Ads::insertAd(null,$_POST['name'], $_POST['code'], $_POST['location'],$_POST['advertisername'],$_POST['adcomment']);
			}
			break;
		case 'delete-do':
			Ads::deleteAd($_REQUEST['id']);
			break;
		case 'editad-form':
			$ad = Ads::getInstance()->getAd($_REQUEST['id']); ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('advertisement'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('name'); ?></label>
								<input class="form-control" title="Ad Name" type='text' name='name' value="<?php echo $ad['name']; ?>"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('adcode'); ?></label>
								<textarea class="form-control" title="Code" name='code' rows='15'>
									<?php echo $ad['code']; ?>
								</textarea>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('optionalinfo'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('location'); ?></label>
								<input class="form-control" title="Location" type='text' name='location' value="<?php echo $ad['location']; ?>"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('advertiser'); ?></label>
								<input class="form-control" title="Advertiser" type='text' name='advertisername'
								       value="<?php echo $ad['advertisername']; ?>"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('comments'); ?></label>
								<textarea class="form-control" title="Comment" name='adcomment'
								          rows="8"><?php echo $ad['adcomment']; ?></textarea>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='ads'/>
				<input type='hidden' name='mthd' value='editad-do'/>
				<input type='hidden' name='id' value='<?php echo $ad['id']; ?>'/>
				<?php Pages::getSubmitButton(); ?>
			</form><?php
			break;
		case 'editad-do':
			if ($_POST['name'] == "" || $_POST['code'] == "") {
				Core::showWarning(gettext('fillallerror'));
			} else {
				Ads::updateAd($_POST['id'],$_POST['name'],$_POST['code'],$_POST['location'],$_POST['advertisername'],$_POST['adcomment']);
			}
			break;
		case 'getcode': ?>
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo gettext('getcode'); ?>
					</div>
					<div class="panel-body">
						<div class="form-group"><?php
							$ad = Ads::getInstance()->getAd($_REQUEST['id']);
							$ad['code'] = '<?php echo Ads::getInstance()->showAds("' . $ad['id'] . '");?>';
							$ad['lcode'] = '<?php echo Ads::getInstance()->showAds("' . $ad['location'] . '");?>'; ?>
							<label><?php echo gettext('individualadcode'); ?></label>
							<textarea class="form-control" title="Ad Code" rows="3"><?php echo $ad['code']; ?></textarea>
							<br/>
							<label><?php echo gettext('location'); ?> (Recommended)</label>
							<textarea class="form-control" title="Ad Location" rows="3"><?php echo $ad['lcode']; ?></textarea>
						</div>
					</div>
					<div class="panel-footer">&nbsp;</div>
				</div>
			</div><?php
			break;
		case "":
		case 'manage':
			$ads = Ads::getInstance()->getAds() ?>
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo gettext('manage'); ?>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr>
										<th><?php echo gettext('name'); ?></th>
										<th><?php echo gettext('location'); ?></th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody><?php
									/* If a page number isn't explicitly passed, assume page 1 */
									$page = $page ?? 1;
									foreach ($ads as $ad) {
										if (mb_strlen($page['code']) > 72) {
											$page['code'] = substr($page['code'], 0, 72) . '...';
										} ?>
										<tr class="odd gradeA">
											<td><?php echo $ad['name']; ?></td>
											<td><?php echo $ad['location']; ?></td>
											<td>
												<?php Pages::getEditButton($ad['id'], 'ads', 'getcode', gettext('getcode'), 'info');?>
												&nbsp;
												<?php Pages::getEditButton($ad['id'], 'ads', 'editad-form', gettext('edit'));?>
												&nbsp;
												<?php Pages::getDeleteButton($ad['id'], 'ads');?>
											</td>
										</tr><?php
									} ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel-footer">&nbsp;</div>
				</div>
			</div><?php
			break;
		default:
	}
} ?>