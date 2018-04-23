<?php
function pages_links()
{
    PHPArcade\Administrations::addLink(gettext('pages'), 'index.php?act=pages');
}

PHPArcade\Administrations::addSubLink(gettext('add'), 'index.php?act=pages&mthd=addpage-form', 'pages');
PHPArcade\Administrations::addSubLink(gettext('manage'), 'index.php?act=pages&mthd=manage', 'pages');
/**
 * @param $mthd
 */
function pages_admin($mthd)
{
    switch ($mthd) {
        case 'addpage-form': ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('contentpage'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('title'); ?></label>
								<input class="form-control" title="Title" name='title'/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('content'); ?></label>
								<textarea class="form-control" title="Content" name='content' rows='10'></textarea>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('metatags'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('description'); ?></label>
								<textarea class="form-control" title="Description" name='description' rows='6'></textarea>
							</div>
							<div class="form-group">
								<label><?php echo gettext('keywords'); ?></label>
								<textarea class="form-control" title="Keywords" name='keywords' rows='6'></textarea>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='pages'/>
				<input type='hidden' name='mthd' value='addpage-do'/>
				<?php PHPArcade\Pages::getSubmitButton();?>
			</form><?php
            break;
        case 'addpage-do':
            if (html_entity_decode($_POST['title']) == "" || html_entity_decode($_POST['content']) == "") {
                \PHPArcade\Core::showWarning(gettext('allfieldserror'));
            } else {
                PHPArcade\Pages::pageAdd(
                    null,
                                html_entity_decode($_POST['title']),
                                html_entity_decode($_POST['content']),
                                html_entity_decode($_POST['keywords']),
                                html_entity_decode($_POST['description'])
                );
            }
            break;
        case 'editpage-form':
            $page = PHPArcade\Pages::getPage($_REQUEST['id']);
            $code = htmlentities('<?php echo \PHPArcade\Core::getLinkPage(' . $page['id'] . ');?>', ENT_QUOTES); ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('getcode'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('followingcode'); ?></label>
								<input class="form-control" title="Code" value='<?php echo $code; ?>'/>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('contentpage'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('title'); ?></label>
								<input class="form-control" title="Title" name='title'
                                       value="<?php echo $page['title']; ?>"/>
							</div>
							<div class="form-group">
								<label><?php echo gettext('content'); ?></label>
									<textarea class="form-control" title="Content" name='content' rows='10'>
										<?php echo $page['content']; ?>
									</textarea>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('metatags'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo gettext('description'); ?></label>
									<textarea class="form-control" title="Description" name='description'
									          rows='6'><?php echo $page['description']; ?></textarea>
							</div>
							<div class="form-group">
								<label><?php echo gettext('keywords'); ?></label>
									<textarea class="form-control" title="Keywords" name='keywords'
									          rows='6'><?php echo $page['keywords']; ?></textarea>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<input type='hidden' name='act' value='pages'/>
				<input type='hidden' name='mthd' value='editpage-do'/>
				<input type='hidden' name='id' value='<?php echo $page['id']; ?>'/>
				<?php PHPArcade\Pages::getSubmitButton();?>
			</form><?php
            break;
        case 'editpage-do':
            if ($_POST['title'] == "" || $_POST['content'] == "") {
                \PHPArcade\Core::showWarning(gettext('allfieldserror'));
            } else {
                PHPArcade\Pages::pageUpdate($_POST['id'], $_POST['title'], $_POST['content'], $_POST['description'], $_POST['keywords']);
                PHPArcade\Core::showSuccess(gettext('updatesuccess'));
            }
            break;
        case 'delete-do':
            PHPArcade\Pages::pageDelete($_REQUEST['id']);
            break;
        default:
            // case "": and case "manage":
            $pages = PHPArcade\Pages::getPages(); ?>
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
									<th><?php echo gettext('title'); ?></th>
									<th><?php echo gettext('content'); ?></th>
									<th>&nbsp;</th>
								</tr>
								</thead>
								<tbody><?php
                                    foreach ($pages as $page) {
                                        if (mb_strlen($page['content']) > 72) {
                                            $page['content'] = substr($page['content'], 0, 72) . '...';
                                        } ?>
										<tr class="odd gradeA">
										<td><?php echo $page['title']; ?></td>
										<td><?php echo $page['content']; ?></td>
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
					</div>
				</div>
			</div><?php
            break;

    }
}
