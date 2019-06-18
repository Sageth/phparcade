<?php
function site_admin($mthd)
{
    $dbconfig = PHPArcade\Core::getDBConfig();
    $prerequisites = PHPArcade\Administrations::getPreReqs();
    $processUser = PHPArcade\Administrations::getProcessUser();
    switch ($mthd) {
        case "":
        case 'home': ?>
            <div class="container">
                <div class="jumbotron container-fluid">
                    <h1 class="display-4">Welcome!</h1>
                    <p>Thanks for downloading phpArcade.  I'd like to let you know of a few things to help you along:</p>
                    <ul>
                        <li>Please file bugs or feature requests at
                            <a href="<?php echo URL_GITHUB_PHPARCADE;?>issues" target="_blank" rel="noopener">
                                <?php echo PHPArcade\Core::showGlyph('github');?> GitHub
                            </a>
                        </li>
                        <li>As of now, there is no direct upgrade path from version to version</li>
                        <li>Help -- in any form -- is <em>always</em> appreciated! <?php echo PHPArcade\Core::showGlyph('smile-o');?></i></li>
                    </ul>
                </div>

                <div class="card-deck">
                    <?php
                    /* Broken Block */
                    if (PHPArcade\Games::getGamesBrokenCount() > 0) {
                        ?>
                        <div class="card" style="max-width: 18rem;">
                            <div class="card-body bg-<?php echo $prerequisites['broken_games'][0]; ?>">
                                <?php echo PHPArcade\Core::showGlyph($prerequisites['broken_games'][1], '5x'); ?>
                                <?php echo PHPArcade\Games::getGamesBrokenCount(); ?>
                                <?php echo gettext('notworking'); ?>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo SITE_URL_ADMIN; ?>index.php?act=media&mthd=viewbroken">
                                    <span class="pull-left"><?php echo gettext('viewdetails'); ?>></span>
                                    <span class="pull-right"><?php echo PHPArcade\Core::showGlyph('arrow-circle-right'); ?></span>
                                    <div class="clearfix"></div>
                                </a>
                            </div>
                        </div>
                        <?php
                    }

                    /* Inactive Block */
                    if (PHPArcade\Games::getGamesInactiveCount() > 0) {
                        ?>
                        <div class="card" style="max-width: 18rem;">
                            <div class="card-body bg-<?php echo $prerequisites['inactive_games'][0]; ?>">
                                <?php echo PHPArcade\Core::showGlyph($prerequisites['inactive_games'][1], '5x'); ?>
                                <?php echo PHPArcade\Games::getGamesInactiveCount(); ?>
                                <?php echo gettext('inactivegames'); ?>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo SITE_URL_ADMIN; ?>index.php?act=media&mthd=inactive">
                                    <span class="pull-left"><?php echo gettext('viewdetails'); ?></span>
                                    <span class="pull-right"><?php echo PHPArcade\Core::showGlyph('arrow-circle-right'); ?></i></span>
                                    <div class="clearfix"></div>
                                </a>
                            </div>
                        </div>
                        <?php
                    }

                    /* SSL Block */
                    if (PHPArcade\Administrations::getScheme() === 'http://') {
                        ?>
                        <div class="card" style="max-width: 18rem;">
                            <div class="card-body bg-<?php echo $prerequisites['ssl'][0]; ?>">
                                <?php echo gettext('ssl'); ?>
                            </div>
                            <div class="card-footer">
                                <a href="https://www.cloudflare.com/plans" target="_blank" rel="noopener">
                                    <span class="pull-left"><?php echo gettext('viewdetails'); ?></span>
                                    <span class="pull-right"><?php echo PHPArcade\Core::showGlyph('arrow-circle-right'); ?></span>
                                    <div class="clearfix"></div>
                                </a>
                            </div>
                        </div><?php
                    }

                    /* Session Block */
                    if ($prerequisites['folder_session'][0] === 'danger') {
                        ?>
                            <div class="card" style="max-width: 18rem;">
                                <div class="card-body bg-<?php echo $prerequisites['folder_session'][0]; ?>">
                                    <?php echo PHPArcade\Core::showGlyph($prerequisites['folder_session'][1], '5x'); ?>
                                    <?php echo gettext('sessions'); ?>
                                    <?php echo session_save_path() . ' ' . gettext('unwritable'); ?>
                                </div>
                                <div class="card-footer">
                                    <p><?php echo gettext('solutionchown'); ?>:</p>
                                    <p>
                                        <code>
                                            $(which chown) -R <?php echo get_current_user(); ?>:<?php echo $processUser['name'] . ' ' . session_save_path(); ?>
                                        </code>
                                    </p>
                                    <p>
                                        <?php echo gettext('solutionchownwarning'); ?>
                                    </p>
                                </div>
                            </div>
                        <?php
                    } ?>

                    <?php /* Statistics */ ?>
                    <div class="card" style="max-width: 18rem;">
                        <div class="card-header">
                            <?php echo gettext('statistics'); ?>
                        </div>
                        <div class="card-body">
                            <p class="text-info">
                                <?php echo gettext('tg'); ?>: <?php echo number_format(PHPArcade\Games::getGamesCount('all')); ?>
                            </p>
                            <p class="text-info">
                                <?php echo gettext('totalgameplays'); ?>: <?php echo number_format(PHPArcade\Core::getPlayCountTotal()); ?>
                            </p>
                            <p class="text-info">
                                <?php echo gettext('registeredusers'); ?>: <?php echo number_format(PHPArcade\Users::getUsersCount()); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div><?php
            break;
        case 'logout':
            PHPArcade\Users::userSessionEnd();
            break;
        case 'site-config': ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
                <div class="container">
                    <div class="card-deck mt-4 ml-5">
                        <div class="card">
                            <div class="card-header">
                                <?php echo PHPArcade\Core::showGlyph('cogs');?>&nbsp;<?php echo gettext('configuration'); ?>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label><?php echo gettext('sitetitle'); ?></label>
                                    <input class="form-control" title="Site Title" name='sitetitle'
                                           value='<?php echo $dbconfig['sitetitle']; ?>'/>
                                </div>
                                <hr/>
                                <div class="form-group">
                                    <label><?php echo gettext('metadescription'); ?></label>
                                    <textarea class="form-control" title="Metadescription" name='metadesc'
                                              rows='6'><?php echo $dbconfig['metadesc']; ?></textarea>
                                </div>
                                <hr/>
                                <div class="form-group">
                                    <label><?php echo gettext('imgurl'); ?></label>
                                    <input class="form-control" title="Image URL" name='imgurl'
                                           value='<?php echo $dbconfig['imgurl']; ?>'/>
                                    <small class="form-text"><?php echo gettext('trailingslash') . ' ' . gettext('imgurlexample'); ?></small>
                                </div>
                            </div>
                            <div class="card-footer">&nbsp;</div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <?php echo PHPArcade\Core::showGlyph('envelope');?>&nbsp;<?php echo gettext('email'); ?>
                                <small class="form-text pull-right"><?php echo gettext('google_appsforbusiness');?></small>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label><?php echo gettext('emaildomain'); ?></label>
                                    <input class="form-control" title="email domain" name='emaildomain'
                                           value='<?php echo $dbconfig['emaildomain']; ?>'/>
                                    <small class="form-text"><?php echo gettext('domainhelper'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label><?php echo gettext('emailhost'); ?></label>
                                    <input class="form-control" title="email host" name='emailhost'
                                           value='<?php echo $dbconfig['emailhost']; ?>'/>
                                    <small class="form-text"><?php echo gettext('emailhostexample'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label><?php echo gettext('emailport'); ?>
                                        <input class="form-control" title="email port" name='emailport'
                                               value='<?php echo $dbconfig['emailport']; ?>'/>
                                    </label>
                                    <small class="form-text"><?php echo gettext('emailportexample'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label><?php echo gettext('emaildebug'); ?></label>
                                    <select class="form-control" title="Email Debug" name="emaildebug"><?php
                                        switch ($dbconfig['emaildebug']) {
                                            case 0:?>
                                                <option value='0' selected>0</option>
                                                <option value='1'>1</option>
                                                <option value='2'>2</option><?php
                                                break;
                                            case 1:?>
                                                <option value='0'>0</option>
                                                <option value='1' selected>1</option>
                                                <option value='2'>2</option><?php
                                                break;
                                            case 2:?>
                                                <option value='0'>0</option>
                                                <option value='1'>1</option>
                                                <option value='2' selected>2</option><?php
                                                break;
                                            default:?>
                                                <option value='ERR' selected>ERROR</option>
                                                <option value='0'>0</option>
                                                <option value='1'>1</option>
                                                <option value='2'>2</option><?php
                                        } ?>
                                    </select>
                                    <small class="form-text">
                                        <?php echo gettext('emaildebugexample'); ?>
                                    </small>
                                </div>
                            </div>
                            <div class="card-footer">&nbsp;</div>
                        </div>
                    </div>
                    <input type='hidden' name='act' value='site'/>
                    <input type='hidden' name='mthd' value='site-config-do'/>
                    <?php PHPArcade\Pages::getSubmitButton(); ?>
                </div>
            </form><?php
            break;
        case 'site-config-do':
            PHPArcade\Administrations::updateConfig('emaildomain', $_POST['emaildomain']);
            PHPArcade\Administrations::updateConfig('emailhost', $_POST['emailhost']);
            PHPArcade\Administrations::updateConfig('emaildebug', $_POST['emaildebug']);
            PHPArcade\Administrations::updateConfig('emailport', $_POST['emailport']);
            PHPArcade\Administrations::updateConfig('imgurl', $_POST['imgurl']);
            PHPArcade\Administrations::updateConfig('metadesc', $_POST['metadesc']);
            PHPArcade\Administrations::updateConfig('sitetitle', $_POST['sitetitle']);
            PHPArcade\Core::showSuccess(gettext('updatesuccess'));
            break;
        case 'theme-config': ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        <?php echo gettext('theme'); ?>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <select class="form-control" title="Theme Name" name="themename">
                                <?php
                                foreach (glob(dirname(__FILE__) . '/themes/*') as $filename) {
                                    if (basename($filename) === 'admin') {
                                        continue;
                                    }
                                    $selected = basename($filename) === $dbconfig['theme'] ? 'selected' : '';
                                    echo "<option value='" . basename($filename) . "' $selected>". basename($filename) ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="form-text">
                            <?php echo gettext('uploadthemesto');?>
                            <span class="badge badge-secondary" data-toggle="tooltip" data-placement="bottom" title="<?php echo gettext('themehelp');?>">
                                    <?php echo gettext('themehelp');?>
                                </span>
                        </small>
                    </div>
                </div>
                <input type='hidden' name='act' value='site'/>
                <input type='hidden' name='mthd' value='theme-config-do'/>
                <?php PHPArcade\Pages::getSubmitButton(); ?>
			</form><?php
            break;
        case 'theme-config-do':
            PHPArcade\Administrations::updateConfig('theme', $_POST['themename']);
            PHPArcade\Core::showSuccess(gettext('updatesuccess'));
            break;
        case 'feature-config':
            $checkedemailact = ($dbconfig['emailactivation'] === 'on') ? 'checked' : "";
            $checkedfacebk = ($dbconfig['facebook_on'] === 'on') ? 'checked' : "";
            $checkedfeed = ($dbconfig['rssenabled'] === 'on') ? 'checked' : "";
            $checkedgtmon = ($dbconfig['gtm_enabled'] === 'on') ? 'checked' : "";
            $checkedpassrecovery = ($dbconfig['passwordrecovery'] === 'on') ? 'checked' : "";
            $checkeduserson = ($dbconfig['membersenabled'] === 'on') ? 'checked' : "";
            ?>
			<form action="<?php echo SITE_URL_ADMIN; ?>index.php" method="POST" enctype="multipart/form-data">
				<div class="card-deck mt-4">
					<div class="card">
						<div class="card-header">
                            <span style="color: blue;">
							    <?php echo PHPArcade\Core::showGlyph('facebook', '1x', 'false', 'b');?>
                            </span>
                            &nbsp;
                            <?php echo gettext('facebook'); ?>
						</div>
						<div class="card-body">
							<div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('database');?>
                                <label><?php echo gettext('facebook_enabled'); ?></label>
                                <div class="checkbox-inline pull-right">
                                    <label for="facebook_on"></label>
                                    <input disabled type="checkbox" name="facebook_on" id="facebook_on" <?php echo $checkedfacebk; ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"/>
                                </div>
                            </div>
                        	<hr/>
							<div class="form-group">
                                <label><?php echo gettext('facebook_appid'); ?></label>
                                <input class="form-control" title="Facebook App ID" name='facebook_appid'
                                       value='<?php echo $dbconfig['facebook_appid']; ?>'/>
                                <small class="form-text"><?php echo gettext('facebook_developers'); ?></small>
                            </div>
                            <hr/>
							<div class="form-group">
                                <label><?php echo gettext('facebook_pageurl'); ?></label>
                                <input class="form-control" title="Facebook Page URL" name="facebook_pageurl"
                                       value="<?php echo $dbconfig['facebook_pageurl']; ?>"/>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
					</div>
				</div>
				<div class="card-deck mt-4">
					<div class="card">
						<div class="card-header">
							<?php echo PHPArcade\Core::showGlyph('google');?>&nbsp;<?php echo gettext('google'); ?>
						</div>
						<div class="card-body">
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('google');?>&nbsp;
                                <label><?php echo gettext('gtm_enabled'); ?></label>
                                <div class="pull-right">
                                    <label for="gtm_enabled"></label>
                                    <input type="checkbox" name="gtm_enabled" id="gtm_enabled" <?php echo $checkedgtmon; ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('gtm_id'); ?></label>
                                <input class="form-control" title="Google Tag Manager ID" name='gtm_id'
                                       value='<?php echo $dbconfig['gtm_id']; ?>'/>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('database');?>
                                <label><?php echo gettext('google_recaptcha_sitekey'); ?></label>
                                <input class="form-control" title="<?php echo gettext('google_recaptcha_sitekey');?>"
                                       name='google_recaptcha_sitekey'
                                       value='<?php echo $dbconfig['google_recaptcha_sitekey']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('google_recaptcha_secretkey'); ?></label>
                                <input class="form-control" title="<?php echo gettext('google_recaptcha_secretkey');?>"
                                       name='google_recaptcha_secretkey'
                                       value='<?php echo $dbconfig['google_recaptcha_secretkey']; ?>'/>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('thumbnails'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('width'); ?></label>
                                <input class="form-control" title="Thumbnail Width" name='twidth'
                                       value='<?php echo $dbconfig['twidth']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('height'); ?></label>
                                <input class="form-control" title="Thumbnail Height" name='theight'
                                       value='<?php echo $dbconfig['theight']; ?>'/>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('mediafiles'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('defaultwidth'); ?></label>
                                <input class="form-control" title="Default Game Width" name='defgwidth'
                                       value='<?php echo $dbconfig['defgwidth']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('defaultheight'); ?></label>
                                <input class="form-control" title="Default Game Height" name='defgheight'
                                       value='<?php echo $dbconfig['defgheight']; ?>'/>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                </div>
                <div class="card-deck mt-4">
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('rssfeeds'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('rss');?>
                                <label><?php echo gettext('enablerss'); ?></label>
                                <div class="checkbox-inline pull-right">
                                    <input type="checkbox" name="rssenabled" title="rssenabled" id="rssenabled" <?php echo $checkedfeed;?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"/>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('asterisk');?>
                                <label><?php echo gettext('numlatest'); ?></label>
                                <input class="form-control" name='rssnumlatest' title="rssnumlatest"
                                       value='<?php echo $dbconfig['rssnumlatest']; ?>'/>
                            </div>
                            <hr />
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('link');?>
                                <label><?php echo gettext('rssfeedurl'); ?></label>
                                <input class="form-control" name='rssfeed' title="rssfeed"
                                       value='<?php echo $dbconfig['rssfeed']; ?>'/>
                                <small class="form-text"><?php echo gettext('rssfeedexample1'); ?></small>
                                <small class="form-text"><?php echo gettext('rssfeedexample2'); ?></small>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo PHPArcade\Core::showGlyph('envelope');?>&nbsp;<?php echo gettext('general'); ?>
                            <small class="form-text pull-right"><?php echo gettext('google_appsforbusiness');?></small>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('users');?>
                                <label><?php echo gettext('usersenabled'); ?></label>
                                <div class="checkbox-inline pull-right">
                                    <label for="membersenabled"></label>
                                    <input type="checkbox" name="membersenabled" id="membersenabled" <?php echo $checkeduserson; ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"/>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('list');?>
                                <label><?php echo gettext('emailactivation'); ?></label>
                                <div class="checkbox-inline pull-right">
                                    <label for="emailactivation"></label>
                                    <input type="checkbox" name="emailactivation" id="emailactivation" <?php echo $checkedemailact; ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"/>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('list');?>
                                <label><?php echo gettext('allowpasswordrecovery'); ?></label>
                                <div class="checkbox-inline pull-right">
                                    <label for="passwordrecovery"></label>
                                    <input type="checkbox" name="passwordrecovery" id="passwordrecovery" <?php echo $checkedpassrecovery; ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"/>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <?php echo PHPArcade\Core::showGlyph('envelope');?>
                                <label for="emailfrom"><?php echo gettext('emailaddressfrom'); ?></label>
                                <input class="form-control" name="emailfrom" id="emailfrom" value='<?php echo $dbconfig['emailfrom']; ?>'/>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='act' value='site' />
                <input type='hidden' name='mthd' value='feature-config-do' />
                <?php PHPArcade\Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'feature-config-do':
            PHPArcade\Administrations::updateConfig('defgwidth', $_POST['defgwidth']);
            PHPArcade\Administrations::updateConfig('defgheight', $_POST['defgheight']);
            PHPArcade\Administrations::updateConfig('emailactivation', array_key_exists('emailactivation', $_POST) ? 'on' : 'off');
            PHPArcade\Administrations::updateConfig('emailfrom', $_POST['emailfrom']);
            PHPArcade\Administrations::updateConfig('facebook_appid', $_POST['facebook_appid']);
            PHPArcade\Administrations::updateConfig('facebook_pageurl', $_POST['facebook_pageurl']);
            PHPArcade\Administrations::updateConfig('facebook_on', array_key_exists('facebook_on', $_POST) ? 'on' : 'off');
            PHPArcade\Administrations::updateConfig('gtm_enabled', array_key_exists('gtm_enabled', $_POST) ? 'on' : 'off');
            PHPArcade\Administrations::updateConfig('gtm_id', $_POST['gtm_id']);
            PHPArcade\Administrations::updateConfig('google_recaptcha_secretkey', $_POST['google_recaptcha_secretkey']);
            PHPArcade\Administrations::updateConfig('google_recaptcha_sitekey', $_POST['google_recaptcha_sitekey']);
            PHPArcade\Administrations::updateConfig('membersenabled', array_key_exists('membersenabled', $_POST) ? 'on' : 'off');
            PHPArcade\Administrations::updateConfig('passwordrecovery', array_key_exists('passwordrecovery', $_POST) ? 'on' : 'off');
            PHPArcade\Administrations::updateConfig('rssenabled', array_key_exists('rssenabled', $_POST) ? 'on' : 'off');
            PHPArcade\Administrations::updateConfig('rssfeed', $_POST['rssfeed']);
            PHPArcade\Administrations::updateConfig('rssnumlatest', $_POST['rssnumlatest']);
            PHPArcade\Administrations::updateConfig('twidth', $_POST['twidth']);
            PHPArcade\Administrations::updateConfig('theight', $_POST['theight']);
            PHPArcade\Core::showSuccess(gettext('updatesuccess'));
            break;
        default:
    }
    unset($prerequisites);
} ?>