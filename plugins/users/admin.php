<?php
function users_admin($mthd)
{
    switch ($mthd) {
        case 'delete-do':
            PHPArcade\Users::userDelete($_REQUEST['id']);
            break;
        case 'edituser-do':
            $_POST['admin'] = property_exists('admin', $_POST) ? 'Yes' : 'No';
            $_POST['active'] = property_exists('active', $_POST) ? 'Yes' : 'No';
            PHPArcade\Users::userEdit($_POST['id']);
            if ($_POST['password'] != '') {
                PHPArcade\Users::userPasswordUpdateByID($_POST['id'], $_POST['password']);
            }
            break;
        case 'edituser-form':
            $user = PHPArcade\Users::getUserbyID($_REQUEST['id']);
            $useractive = ($user['active'] === 'on' || $user['active'] === 'Yes') ? 'checked' : "";
            $useradmin = ($user['admin'] === 'on' || $user['admin'] === 'Yes') ? 'checked' : "";?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="card-deck mt-4">
					<div class="card">
						<div class="card-header">
							<?php echo gettext('edit'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label for="username"><?php echo gettext('username'); ?></label>
                                <input class="form-control" id="username" name="<?php echo gettext('username'); ?>" value="<?php echo $user['username']; ?>" title="username"/>
							</div>
							<div class="form-group">
								<label for="email"><?php echo gettext('email'); ?></label>
                                <input class="form-control" id="email" name="<?php echo gettext('email'); ?>" value="<?php echo $user['email']; ?>" title="email"/>
							</div>
							<div class="form-group">
								<label for="password"><?php echo gettext('password'); ?> (<?php echo gettext('blank'); ?>)</label>
                                <input class="form-control" id="password" name="password" value=""/>
							</div>
							<div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('user');?>
                                <label><?php echo gettext('active'); ?></label>
                                <div class="checkbox-inline pull-right">
                                    <label for="active"></label>
                                    <input type="checkbox" name="active" id="active" <?php echo $useractive; ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"/>
                                </div>
                            </div>
							<hr/>
							<div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('lock');?>
                                <label><?php echo gettext('siteadmin'); ?></label>
                                <div class="checkbox-inline pull-right">
                                    <label for="admin"></label>
                                    <?php /* The toggle colors are purporsely backward so if you see something red, you know it's not normal */ ?>
                                    <input type="checkbox" name="admin" id="admin" <?php echo $useradmin; ?> data-toggle="toggle" data-onstyle="danger" data-offstyle="success"/>
                                </div>
                            </div>
						</div>
						<div class="card-footer">&nbsp;</div>
					</div>
					<div class="card">
						<div class="card-header">
							<?php echo gettext('socialconfig'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label for="gamesplayed">
									<?php echo gettext('gamesplayed');?>
								</label>
                                <input disabled class="form-control"
                                       id="gamesplayed" name='totalgames' value='<?php echo $user['totalgames']; ?>' />
							</div>
							<div class="form-group">
								<label for="twitter">
									<?php echo gettext('twitter'); ?>
								</label>
                                <input class="form-control" id="twitter" name='twitter_id' value='<?php echo $user['twitter_id']; ?>'/>
							</div>
                            <div>
                                <label for="gravatar"><?php echo gettext('gravatar');?></label>
                                <img alt="<?php $user['name'];?>'s Gravatar"
                                     class="img img-responsive img-circle"
                                     data-src="<?php echo PHPArcade\Users::userGetGravatar($user['username']); ?>"
                                     height="80"
                                     id="gravatar"
                                     style="float:right"
                                     width="80"
                                />
                            </div>
						</div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                </div>
				<input type='hidden' name='id' value='<?php echo $user['id'];?>'/>
				<input type='hidden' name='act' value='users'/>
				<input type='hidden' name='mthd' value='edituser-do'/>
				<?php PHPArcade\Pages::getSubmitButton(); ?>
			</form><?php
            break;
        case "":
        case 'manage':
            $users = PHPArcade\Users::getUsersAll(); ?>
            <div class="container">
                <div class="input-group col-lg-10">
                    <div class="input-group col-md-6 mt-4">
                        <span class="input-group-addon info col-lg-4" id="user-addon">
                           <div class="align-middle mt-2">
                               <i class="fa fa-search" aria-hidden="true"></i>
                               <?php echo gettext('search');?>
                           </div>
                        </span>
                        <label for="userList"></label>
                        <input type="text" class="form-control" id="userList" name="<?php echo gettext('displayuserlist');?>" onkeyup="filterTable()" placeholder="<?php echo gettext('userfilter');?>" aria-describedby="user-addon">
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="table-responsive mt-4">
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
                                    <tr>
                                        <td><?php echo $user['username']; ?></td>
                                        <td><?php echo $user['totalgames']; ?></td>
                                        <td><?php echo $user['ip']; ?></td>
                                        <td>
                                            <?php PHPArcade\Pages::getEditButton($user['id'], 'users', 'edituser-form', gettext('edit')); ?>
                                            &nbsp;
                                            <?php PHPArcade\Pages::getDeleteButton($user['id'], 'users'); ?>
                                        </td>
                                    </tr><?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <script async src="<?php echo JS_TABLEFILTER;?>"></script>
        <?php
            break;
        default:
    }
} ?>
