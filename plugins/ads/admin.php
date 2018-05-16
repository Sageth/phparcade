<?php
function ads_links()
{
    PHPArcade\Administrations::addLink(gettext('ads'), 'index.php?act=ads');
}
function ads_admin($mthd)
{
    switch ($mthd) {
        case 'addad-form': ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="card-deck mt-4">
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('advertisement') ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control" title="Name" name="name"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('adcode'); ?></label>
                                <textarea class="form-control" title="Code" name='code' cols='60' rows='10'></textarea>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
					<div class="card">
						<div class="card-header">
							<?php echo gettext('optionalinfo'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label><?php echo gettext('location'); ?></label>
								<input class="form-control" title="Location" name='location'/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('advertiser'); ?></label>
								<input class="form-control" title="Advertiser Name" name='advertisername'/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('comments'); ?></label>
								<textarea class="form-control" title="Comment" name="adcomment" rows="3"></textarea>
							</div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='ads'/>
				<input type='hidden' name='mthd' value='addad-do'/>
				<?php PHPArcade\Pages::getSubmitButton(); ?>
			</form><?php
            break;
        case 'addad-do':
            if ($_POST['name'] == "" || $_POST['code'] == "") {
                PHPArcade\Core::showWarning(gettext('fillallerror'));
            } else {
                PHPArcade\Ads::insertAd(null, $_POST['name'], $_POST['code'], $_POST['location'], $_POST['advertisername'], $_POST['adcomment']);
            }
            break;
        case 'delete-do':
            PHPArcade\Ads::deleteAd($_REQUEST['id']);
            break;
        case 'editad-form':
            $ad = PHPArcade\Ads::getInstance()->getAd($_REQUEST['id']); ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="card-deck mt-4">
					<div class="card">
						<div class="card-header">
							<?php echo gettext('advertisement'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label><?php echo gettext('name'); ?></label>
								<input class="form-control" title="Ad Name" name='name' value="<?php echo $ad['name']; ?>"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('adcode'); ?></label>
								<textarea class="form-control" title="Code" name="code" rows="15">
									<?php echo $ad['code']; ?>
								</textarea>
							</div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
					<div class="card">
						<div class="card-header">
							<?php echo gettext('optionalinfo'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label><?php echo gettext('location'); ?></label>
								<input class="form-control" title="Location"
                                       name='location' value="<?php echo $ad['location']; ?>"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('advertiser'); ?></label>
								<input class="form-control" title="Advertiser" name='advertisername'
                                       value="<?php echo $ad['advertisername']; ?>"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('comments'); ?></label>
								<textarea class="form-control" title="Comment" name='adcomment'
								          rows="8"><?php echo $ad['adcomment']; ?></textarea>
							</div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='ads'/>
				<input type='hidden' name='mthd' value='editad-do'/>
				<input type='hidden' name='id' value='<?php echo $ad['id']; ?>'/>
				<?php PHPArcade\Pages::getSubmitButton(); ?>
			</form><?php
            break;
        case 'editad-do':
            if ($_POST['name'] == "" || $_POST['code'] == "") {
                PHPArcade\Core::showWarning(gettext('fillallerror'));
            } else {
                PHPArcade\Ads::updateAd($_POST['id'], $_POST['name'], $_POST['code'], $_POST['location'], $_POST['advertisername'], $_POST['adcomment']);
            }
            break;
        case 'getcode': ?>
			<div class="card">
                <div class="card-header">
                    <?php echo gettext('getcode'); ?>
                </div>
                <div class="card-body">
                    <div class="form-group"><?php
                        $ad = PHPArcade\Ads::getInstance()->getAd($_REQUEST['id']);
                        $ad['code'] = '<?php echo Ads::getInstance()->showAds("' . $ad['id'] . '");?>';
                        $ad['lcode'] = '<?php echo Ads::getInstance()->showAds("' . $ad['location'] . '");?>'; ?>
                        <label><?php echo gettext('individualadcode'); ?></label>
                        <textarea class="form-control" title="Ad Code" rows="3"><?php echo $ad['code']; ?></textarea>
                        <br/>
                        <label><?php echo gettext('location'); ?> (Recommended)</label>
                        <textarea class="form-control" title="Ad Location" rows="3"><?php echo $ad['lcode']; ?></textarea>
                    </div>
                </div>
                <div class="card-footer">&nbsp;</div>
			</div><?php
            break;
        case "":
        case 'manage':
            $ads = PHPArcade\Ads::getInstance()->getAds() ?>
				<div class="card mt-4">
					<div class="card-header">
						<?php echo gettext('manage'); ?>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead class="thead-light">
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
										<tr>
											<td><?php echo $ad['name']; ?></td>
											<td><?php echo $ad['location']; ?></td>
											<td>
												<?php PHPArcade\Pages::getEditButton($ad['id'], 'ads', 'getcode', gettext('getcode'), 'info'); ?>
												&nbsp;
												<?php PHPArcade\Pages::getEditButton($ad['id'], 'ads', 'editad-form', gettext('edit')); ?>
												&nbsp;
												<?php PHPArcade\Pages::getDeleteButton($ad['id'], 'ads'); ?>
											</td>
										</tr><?php
                                    } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-footer">&nbsp;</div>
				</div><?php
            break;
        default:
    }
} ?>