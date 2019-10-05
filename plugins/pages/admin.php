<?php

/**
 * @param $mthd
 */
function pages_admin($mthd)
{
    switch ($mthd) {
        case 'addpage-form': ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="card-deck">
					<div class="card">
						<div class="card-header">
							<?php echo gettext('contentpage'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label><?php echo gettext(P_TITLE); ?></label>
								<input class="form-control" title="Title" name='title'/>
							</div>
							<div class="form-group">
								<label><?php echo gettext(P_CONTENT); ?></label>
								<textarea class="form-control" title="Content" name='content' rows='10'></textarea>
							</div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
					<div class="card">
						<div class="card-header">
							<?php echo gettext('metatags'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label><?php echo gettext(P_DESCRIPTION); ?></label>
								<textarea class="form-control" title="Description" name='description' rows='6'></textarea>
							</div>
							<div class="form-group">
								<label><?php echo gettext(P_KEYWORDS); ?></label>
								<textarea class="form-control" title="Keywords" name='keywords' rows='6'></textarea>
							</div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='pages'/>
				<input type='hidden' name='mthd' value='addpage-do'/>
				<?php PHPArcade\Pages::getSubmitButton();?>
			</form><?php
            break;
        case 'addpage-do':
            if (html_entity_decode($_POST[P_TITLE]) == "" || html_entity_decode($_POST[P_CONTENT]) == "") {
                PHPArcade\Core::showWarning(gettext('allfieldserror'));
            } else {
                PHPArcade\Pages::pageAdd(
                    null,
                                html_entity_decode($_POST[P_TITLE]),
                                html_entity_decode($_POST[P_CONTENT]),
                                html_entity_decode($_POST[P_KEYWORDS]),
                                html_entity_decode($_POST[P_DESCRIPTION])
                );
            }
            break;
        case 'editpage-form':
            $page = PHPArcade\Pages::getPage($_REQUEST['id']);
            $code = htmlentities('<?php echo PHPArcade\Core::getLinkPage(' . $page['id'] . ');?>', ENT_QUOTES); ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="card-deck">
					<div class="card">
						<div class="card-header">
							<?php echo gettext('getcode'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label><?php echo gettext('followingcode'); ?></label>
								<input class="form-control" title="Code" value='<?php echo $code; ?>'/>
							</div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
					<div class="card">
						<div class="card-header">
							<?php echo gettext('contentpage'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label><?php echo gettext(P_TITLE); ?></label>
								<input class="form-control" title="Title" name='title'
                                       value="<?php echo $page[P_TITLE]; ?>"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext(P_CONTENT); ?></label>
									<textarea class="form-control" title="Content" name='content' rows='10'>
										<?php echo $page[P_CONTENT]; ?>
									</textarea>
							</div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
					<div class="card">
						<div class="card-header">
							<?php echo gettext('metatags'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label><?php echo gettext(P_DESCRIPTION); ?></label>
                                <textarea class="form-control" title="Description" name="description" rows="6">
                                    <?php echo $page[P_DESCRIPTION]; ?>
                                </textarea>
							</div>
							<div class="form-group">
								<label><?php echo gettext(P_KEYWORDS); ?></label>
                                <textarea class="form-control" title="Keywords" name="keywords" rows='6'>
                                    <?php echo $page[P_KEYWORDS]; ?>
                                </textarea>
							</div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='pages'/>
				<input type='hidden' name='mthd' value='editpage-do'/>
				<input type='hidden' name='id' value='<?php echo $page['id']; ?>'/>
				<?php PHPArcade\Pages::getSubmitButton();?>
			</form><?php
            break;
        case 'editpage-do':
            if ($_POST[P_TITLE] == "" || $_POST[P_CONTENT] == "") {
                PHPArcade\Core::showWarning(gettext('allfieldserror'));
            } else {
                PHPArcade\Pages::pageUpdate($_POST['id'], $_POST[P_TITLE], $_POST[P_CONTENT], $_POST[P_DESCRIPTION], $_POST[P_KEYWORDS]);
                PHPArcade\Core::showSuccess(gettext('updatesuccess'));
            }
            break;
        case 'delete-do':
            PHPArcade\Pages::pageDelete($_REQUEST['id']);
            break;
        default:
            // case "": and case "manage":
            $pages = PHPArcade\Pages::getPages(); ?>
			<div class="col-lg-12 mt-4">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col"><?php echo gettext(P_TITLE); ?></th>
                                <th scope="col"><?php echo gettext(P_CONTENT); ?></th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody><?php
                            foreach ($pages as $page) {
                                if (mb_strlen($page[P_CONTENT]) > 72) {
                                    $page[P_CONTENT] = substr($page[P_CONTENT], 0, 72) . '...';
                                } ?>
                                <tr>
                                    <td><?php echo $page[P_TITLE]; ?></td>
                                    <td><?php echo $page[P_CONTENT]; ?></td>
                                    <td>
                                        <?php PHPArcade\Pages::getEditButton($page['id'], 'pages', 'editpage-form', gettext('edit')); ?>
                                        &nbsp;
                                        <?php PHPArcade\Pages::getDeleteButton($page['id'], 'pages'); ?>
                                    </td>
                                </tr><?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div><?php
            break;

    }
}
