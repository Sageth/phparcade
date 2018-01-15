<?php
function users_links()
{
    Administrations::addLink(gettext('users'), 'index.php?act=users');
}

Administrations::addSubLink(gettext('manage'), 'index.php?act=users&mthd=manage', 'users');
function users_admin($mthd)
{
    switch ($mthd) {
        case 'delete-do':
            Users::userDelete($_REQUEST['id']);
            break;
        case 'edituser-do':
            $_POST['admin'] = array_key_exists('admin', $_POST) ? 'Yes' : 'No';
            $_POST['active'] = array_key_exists('active', $_POST) ? 'Yes' : 'No';
            Users::userEdit($_POST['id']);
            if ($_POST['password'] != '') {
                Users::userPasswordUpdateByID($_POST['id'], $_POST['password']);
            }
            break;
        case 'edituser-form':
            $user = Users::getUserbyID($_REQUEST['id']);
            $useractive = ($user['active'] === 'on' || $user['active'] === 'Yes') ? 'checked' : "";
            $useradmin = ($user['admin'] === 'on' || $user['admin'] === 'Yes') ? 'checked' : "";?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('edit'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label>
									<?php echo gettext('username'); ?>
									<input class="form-control" name='username' value='<?php echo $user['username']; ?>'/>
								</label>
							</div>
                            <div>
                                <label>
                                    <?php echo gettext('gravatar');?> :
                                    <img src="<?php echo Users::userGetGravatar($user['username']); ?>"
                                         class="img img-responsive img-circle"
                                         style="float:right"
                                         height="80"
                                         width="80"
                                    />
                                </label>
                            </div>
							<div class="form-group">
								<label>
									<?php echo gettext('email'); ?>
									<input class="form-control" name='email' value='<?php echo $user['email']; ?>'/>
								</label>
							</div>
							<div class="form-group">
								<label>
									<?php echo gettext('password'); ?> (<?php echo gettext('blank'); ?>)
									<input class="form-control" name='password' value=''/>
								</label>
							</div>
							<div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo Core::showGlyph('user');?>
								        <label><?php echo gettext('active'); ?></label>
								        <div class="checkbox-inline pull-right">
									        <label for="active"></label>
									        <input type="checkbox" name="active" id="active" <?php echo $useractive; ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"/>
								        </div>
							        </div>
                                </div>
                            </div>
							<hr/>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<?php echo Core::showGlyph('lock');?>
										<label><?php echo gettext('siteadmin'); ?></label>
										<div class="checkbox-inline pull-right">
											<label for="admin"></label>
                                            <?php /* The toggle colors are purporsely backward so if you see something red, you know it's not normal */ ?>
											<input type="checkbox" name="admin" id="admin" <?php echo $useradmin; ?> data-toggle="toggle" data-onstyle="danger" data-offstyle="success"/>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-footer">&nbsp;</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo gettext('socialconfig'); ?>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label>
									<?php echo gettext('gamesplayed');?>
									<input disabled class="form-control"
                                           name='totalgames' value='<?php echo $user['totalgames']; ?>' />
								</label>
							</div>
							<div class="form-group">
								<label>
									<?php echo gettext('msn');?> <?php echo gettext('messenger');?>
									<input class="form-control" name='msn' value='<?php echo $user['msn'];?>'/>
								</label>
							</div>
							<div class="form-group">
								<label>
									<?php echo gettext('twitter'); ?>
									<input class="form-control" name='twitter_id' value='<?php echo $user['twitter_id']; ?>'/>
								</label>
							</div>
						</div>
					</div>
					<div class="panel-footer">&nbsp;</div>
				</div>
				<input type='hidden' name='id' value='<?php echo $user['id'];?>'/>
				<input type='hidden' name='act' value='users'/>
				<input type='hidden' name='mthd' value='edituser-do'/>
				<?php Pages::getSubmitButton(); ?>
			</form><?php
            break;
        case "":
        case 'manage':
            $users = Users::getUsersAll(); ?>
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo gettext('manage'); ?>
					</div>
					<div class="panel-body">
						<div class="table-responsive col-lg-10">
                            <div class="input-group col-md-6">
                                <span class="input-group-addon info" id="user-addon">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    <?php echo gettext('search');?>
                                </span>
                                <input type="text" class="form-control" id="userList" onkeyup="filterTable()" placeholder="<?php echo gettext('userfilter');?>" aria-describedby="user-addon">
                            </div>
                            <div class="row">&nbsp;</div>
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<th><?php echo gettext('username'); ?></th>
									<th><?php echo gettext('gamesplayed'); ?></th>
									<th><?php echo gettext('ipaddress'); ?></th>
									<th>&nbsp;</th>
								</thead>
								<tbody><?php
                                    foreach ($users as $user) {
                                        ?>
										<tr class="odd gradeA">
											<td><?php echo $user['username']; ?></td>
											<td><?php echo $user['totalgames']; ?></td>
											<td><?php echo $user['ip']; ?></td>
											<td>
												<?php Pages::getEditButton($user['id'], 'users', 'edituser-form', gettext('edit')); ?>
												&nbsp;
												<?php Pages::getDeleteButton($user['id'], 'users'); ?>
											</td>
										</tr><?php
                                    } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
            <script src="<?php echo JS_TABLEFILTER;?>" defer></script>
        <?php
            break;
        default:
    }
} ?>