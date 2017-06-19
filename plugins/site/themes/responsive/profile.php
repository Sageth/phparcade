<?php
if (!isset($_SESSION)) {session_start();}
global $params;
$dbconfig = Core::getDBConfig(); ?>
<hr>
<div class="row">
    <div class="col-lg-12"><?php
        if ($params[1] === 'view') {
            $user = Users::getUserbyID($params[2]);
            if ($user == false) {
                Core::showInfo(gettext('noexist'));
            } else {
                $i = 0;
                $avatar = trim($user['avatarurl'] === '') ? SITE_URL . 'includes/images/noav.png' : SITE_URL .
                                                                                                    $user['avatarurl'];
                $status = Sessions::isOnline($user['id']) ? gettext('online') : gettext('offline');
                $games = Games::getGamesChamp($user['id']); ?>
                <div class="col-sm-10">
                    <h1>
                        <?php echo $user['username']; ?>
                    </h1>
                </div>
                <div class="col-sm-2 pull-right">
                    <img class="img img-responsive img-circle"
                         src="<?php echo $avatar; ?>"
                         alt="<?php echo $user['username']; ?>"
                         height="80px"
                         width="80px"
                    />
                </div>
                <div class="row">
                <!--left col-->
                <div class="col-sm-3">
                    <ul class="list-group">
                        <li class="list-group-item text-muted"><?php echo gettext('profile'); ?></li>
                        <li class="list-group-item text-right">
                            <span class="pull-left"><?php echo gettext('joindate'); ?></span>
                            <?php echo date('Y-m-d', $user['regtime']); ?>
                        </li>
                        <li class="list-group-item text-right">
                            <span class="pull-left"><?php echo gettext('status'); ?></span>
                            <?php echo $status; ?>
                        </li>
                        <li class="list-group-item text-right">
                            <span class="pull-left"><?php echo gettext('lastlogin'); ?></span>
                            <?php echo $user['last_login']; ?>
                        </li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item text-muted">
                            <?php echo gettext('activity'); ?><?php echo Core::showGlyph('dashboard'); ?>
                        </li>
                        <li class="list-group-item text-right">
                                        <span class="pull-left"><?php
                                            echo gettext('gamesplayed'); ?>
                                        </span><?php
                            echo $user['totalgames']; ?>
                        </li>
                    </ul>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('socialmedia'); ?>
                        </div>
                        <div class="panel-body"><?php
                            if ($user['facebook_id'] != "") { ?>
                            <a href="<?php echo URL_FACEBOOK . $user['facebook_id']; ?>" target="_blank">
                                <?php echo Core::showGlyph('facebook', '2x', 'false'); ?>
                                </a><?php
                            } else { ?>
                                <?php echo Core::showGlyph('facebook', '2x', 'false'); ?><?php
                            }
                            if ($user['github_id'] != "") { ?>
                            <a href="<?php echo URL_GITHUB . $user['github_id']; ?>" target="_blank">
                                <?php echo Core::showGlyph('github', '2x', 'false'); ?>
                                </a><?php
                            } else { ?>
                                <?php echo Core::showGlyph('github', '2x', 'false'); ?><?php
                            }
                            if ($user['twitter_id'] != "") { ?>
                            <a href="<?php echo URL_TWITTER . $user['twitter_id']; ?>" target="_blank">
                                <?php echo Core::showGlyph('twitter', '2x', 'false'); ?>
                                </a><?php
                            } else { ?>
                                <?php echo Core::showGlyph('twitter', '2x', 'false'); ?><?php
                            } ?>
                            <?php echo Core::showGlyph('pinterest', '2x', 'false'); ?>
                            <?php echo Core::showGlyph('google-plus', '2x', 'false'); ?>
                        </div>
                    </div>
                    <?php echo Ads::getInstance()->showAds('Responsive'); ?>
                </div>
                <div class="col-md-9">
                    <?php echo Ads::getInstance()->showAds('Responsive'); ?>
                    <div class="clearfix invisible"></div>
                    <h3><?php echo $user['username'] . ' ' . gettext('bestplayer'); ?> </h3>
                    <div class="panel panel-info">
                        <div class="panel-body"><?php
                            foreach ($games as $game) { ?>
                                <div class="col-md-4 col-md-4">
                                <div class="thumbnail"><?php
                                    $game = Games::getGame($game['nameid']);
                                    $link = Core::getLinkGame($game['id']); ?>
                                    <a href="<?php echo $link; ?>"><?php
                                        $img = $dbconfig['imgurl'] . $game['nameid'] . '.png'; ?>
                                        <img class="img img-responsive img-rounded"
                                             src="<?php echo $img; ?>"
                                             alt="<?php echo gettext('play')
                                                             . ' '
                                                             . $game['name']
                                                             . ' '
                                                             . gettext('onlineforfree'); ?>"
                                             title="<?php echo gettext('play')
                                                               . ' '
                                                               . $game['name']
                                                               . ' '
                                                               . gettext('onlineforfree'); ?>"
                                             width="<?php echo $dbconfig['twidth']; ?>"
                                             height="<?php echo $dbconfig['theight']; ?>"
                                        />
                                    </a>
                                    <div class="caption">
                                        <h3><?php echo $game['name']; ?></h3>
                                        <p><?php echo $game['desc']; ?></p>
                                        <p>
                                            <a href="<?php echo $link; ?>"
                                               class="btn btn-primary btn-lg btn-block"
                                               role="button">
                                                <?php echo gettext('playnow'); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                </div><?php
                                ++$i;
                                if ($i == 3) { ?>
                                    <div class="clearfix invisible"></div><?php
                                    //Resets boxes
                                    $i = 0;
                                }
                            } ?>
                        </div>
                    </div>
                </div>
                </div><?php
            }
        } else {
            if ($params[1] === 'edit') {
                $user = $_SESSION['user'];
                if ($params[2] == "" || !isset($params[2])) {?>
                    <form action="<?php echo SITE_URL; ?>" method="post" enctype="multipart/form-data" role="form" autocomplete="off">
                        <div class="col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php echo gettext('accountinformation'); ?>
                                </div>
                                <div class="panel-body">
                                    <!-- fake fields are a workaround for chrome autofill getting the wrong fields
                                         See https://code.google.com/p/chromium/issues/detail?id=352347 -->
                                    <div class="form-group">
                                        <label for="fakeusernameremembered"></label>
                                        <label for="fakepasswordremembered"></label>
                                        <input style="display:none;" type="text" title="fake" name="fakeusernameremembered"/>
                                        <input style="display:none;" type="password" title="fake" name="fakepasswordremembered"/>
                                    </div>
                                    <!-- End Fake password workaround -->
                                    <div class="form-group">
                                        <label for="email"><?php echo gettext('email'); ?></label>
                                        <input class="form-control" type="email" title="email" name="email" value="<?php echo $user['email']; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="birth_date"><?php echo gettext('datebirth'); ?></label>
                                        <input class="form-control" type="text" title="<?php echo gettext('datebirth'); ?>" name="birth_date" placeholder="<?php echo $user['birth_date']; ?>" disabled/>
                                    </div>
                                    <div class="form-group">
                                        <label for="email"><?php echo gettext('password'); ?></label>
                                        <input class="form-control" title="<?php echo gettext('password'); ?>" type="password" name="password" placeholder="&nbsp;"/>
                                        <p class="help-block"><?php echo gettext('blank'); ?></p>
                                    </div>
                                </div>
                                <div class="panel-footer">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php echo gettext('avatarsetup'); ?>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="avatar"><?php echo gettext('uploadavatar'); ?></label>
                                        <input class="form-control" type="file" name="uploadavatar"/>
                                    </div>
                                </div>
                                <div class="panel-footer">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php echo gettext('socialinfo'); ?>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="aim"><?php echo gettext('aim'); ?></label>
                                        <input class="form-control" type='text' title="<?php echo gettext('aim'); ?>" name='aim' value='<?php echo $user['aim']; ?>'/>
                                    </div>
                                    <div class="form-group">
                                        <label for="msn"><?php echo gettext('msn'); ?></label>
                                        <input class="form-control" type='text' title="<?php echo gettext('msn'); ?>" name='msn' value='<?php echo $user['msn']; ?>'/>
                                    </div>
                                    <div class="form-group">
                                        <label for="yahoo"><?php echo gettext('yahoo'); ?></label>
                                        <input class="form-control" type='text' title="<?php echo gettext('yahoo'); ?>" name='yahoo' value='<?php echo $user['yahoo']; ?>'/>
                                    </div>
                                    <div class="form-group">
                                        <label for="facebook_id"><?php echo gettext('facebook'); ?></label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><?php echo gettext('facebook_link'); ?></span>
                                            <input type="text" class="form-control" placeholder="Friendly Name" name="facebook_id" value="<?php echo $user['facebook']; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="github"><?php echo gettext('github_id'); ?></label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><?php echo gettext('github_link'); ?></span>
                                            <input type="text" class="form-control" placeholder="Friendly Name" name="github_id" value="<?php echo $user['github']; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="twitter"><?php echo gettext('twitter'); ?></label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><?php echo gettext('twitter_link'); ?></span>
                                            <input type="text" class="form-control" placeholder="Friendly Name" name="twitter_id" value="<?php echo $user['twitter']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">&nbsp;</div>
                            </div>
                        </div>
                        <input type='hidden' name='params' value='profile/edit/editdone'/>
                        <button type="submit" class="btn btn-primary" value="<?php echo gettext('profileedit'); ?>">
                            <?php echo gettext('submit'); ?>
                        </button>
                    </form><?php
                }
            } else {
                if ($params[2] === 'editdone') {
                    echo gettext('updatesuccess');
                } else {
                    echo gettext('error');
                }
            }
        } ?>
    </div>
</div>