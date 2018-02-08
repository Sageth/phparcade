<?php
if (!isset($_SESSION)) {
    session_start();
}
global $params;
$dbconfig = Core::getInstance()->getDBConfig(); ?>
<div class="card-block">
    <div class="col-lg-12 mt-4">
        <?php
        echo Ads::getInstance()->showAds('Responsive');
        if ($params[1] === 'view' && $params[1] != 'edit') {
            $user = Users::getUserbyID($params[2]);
            if ($user === false) {
                Core::showError(gettext('noexist'));
            } else {
                $games = Games::getGamesChamp($user['id']); ?>
                <div class="col mt-4">
                    <img class="img img-responsive img-circle"
                         src="<?php echo Users::userGetGravatar($user['username'], 80); ?>"
                         alt="<?php echo $user['username']; ?>'s Gravatar"
                         title="<?php echo $user['username']; ?>'s Gravatar"
                    />
                    <h1>
                        <?php echo $user['username']; ?>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <!--left col-->
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <p class="card-text">
                                    <?php echo gettext('profile'); ?>
                                </p>
                            </div>
                            <div class="card-body">
                                <span class="pull-left">
                                    <?php echo gettext('joindate'); ?>
                                </span>
                                <span class="pull-right">
                                    <?php echo date('Y-m-d', $user['regtime']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <span class="pull-left">
                                    <?php echo gettext('lastlogin'); ?>
                                </span>
                                <span class="pull-right">
                                    <?php echo $user['last_login']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <p class="card-text">
                                    <?php
                                    echo Core::showGlyph('dashboard');
                                    echo "&nbsp;";
                                    echo gettext('activity');
                                    ?>
                                </p>
                            </div>
                            <div class="card-body">
                                <span class="pull-left">
                                    <?php echo gettext('gamesplayed'); ?>
                                </span>
                                <span class="pull-right">
                                    <?php echo $user['totalgames']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <p class="card-text">
                                    <?php echo gettext('socialmedia'); ?>
                                </p>
                            </div>
                            <div class="card-body text-center">
                                <?php
                                if ($user['facebook_id'] != "") { ?>
                                    <a href="<?php echo URL_FACEBOOK . $user['facebook_id']; ?>" target="_blank" rel="noopener">
                                        <?php echo Core::showGlyph('facebook', '2x', 'false'); ?>
                                    </a><?php
                                } else {
                                    echo Core::showGlyph('facebook', '2x', 'false'); ?><?php
                                }

                                if ($user['github_id'] != "") { ?>
                                    <a href="<?php echo URL_GITHUB . $user['github_id']; ?>" target="_blank" rel="noopener">
                                        <?php echo Core::showGlyph('github', '2x', 'false'); ?>
                                    </a><?php
                                } else {
                                    echo Core::showGlyph('github', '2x', 'false');
                                }

                                if ($user['twitter_id'] != "") { ?>
                                    <a href="<?php echo URL_TWITTER . $user['twitter_id']; ?>" target="_blank" rel="noopener">
                                        <?php echo Core::showGlyph('twitter', '2x', 'false'); ?>
                                    </a><?php
                                } else {
                                    echo Core::showGlyph('twitter', '2x', 'false');
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 mt-4">
                        <h3 class="card-header text-white bg-dark">
                            <?php echo $user['username'] . ' ' . gettext('bestplayer'); ?>
                        </h3>
                        <?php
                        $i=0;
                        foreach ($games as $game) {
                            $game = Games::getGame($game['nameid']);
                            $link = Core::getLinkGame($game['id']);
                            if ($i === 0) {
                                echo '<div class="card-deck mt-4">';
                            } ?>
                            <div class="card">
                                <div class="card-header">
                                    <h3>
                                        <?php echo $game['name']; ?>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php
                                     ?>
                                    <a href="<?php echo $link; ?>">
                                        <?php
                                        $img = $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>
                                        <img class="mx-auto d-block img-thumbnail img-rounded"
                                             data-original="<?php echo $img; ?>"
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
                                    <div class="card-text mt-3">
                                        <p><?php echo $game['desc']; ?></p>
                                        <p>
                                            <a href="<?php echo $link; ?>"
                                               class="btn btn-primary btn-lg btn-block">
                                                <?php echo gettext('playnow'); ?>
                                            </a>
                                        </p>
                                    </div>
                            </div><?php
                            ++$i;
                            if ($i === 2) {
                                echo '</div>';
                                $i = 0;
                            } ?>
                            </div><?php
                        } ?>
                    </div>
                </div>
                <script type="application/ld+json" defer>
                    {
                        "@context": "http://schema.org",
                        "@type": "Person",
                        "name": "<?php echo $user['username']; ?>",
                        "url": "<?php echo SITE_URL; ?>profile/view/<?php echo $user['id']; ?>/<?php echo $user['username']; ?>.html"
                        <?php
                        if (!empty($user['facebook_id']) || (!empty($user['github_id'])) || (!empty($user['twitter_id']))) { ?>,
                            "sameAs": [
                                <?php if (!empty($user['facebook_id'])) { ?>
                                    "http://www.facebook.com/<?php echo $user['facebook_id']; ?>",
                                    <?php
                                }

                                if (!empty($user['github_id'])) { ?>
                                    "http://www.github.com/<?php echo $user['github_id']; ?>",
                                    <?php
                                }

                                if (!empty($user['twitter_id'])) { ?>
                                    "http://www.twitter.com/<?php echo $user['twitter_id']; ?>"
                                    <?php
                                } ?>
                            ]
                            <?php
                        } ?>
                    }
                </script>
                <?php
            }
        } else {
            if ($params[1] === 'edit') {
                $user = Users::getUserbyID($_SESSION['user']['id']);
                if ($params[2] == "" || !isset($params[2])) {
                    ?>
                    <div class="card border-0 mt-4">
                        <?php Core::showInfo('Change your avatar at <a href="https://gravatar.com" target="_blank" rel="noopener">Gravatar.com</a>');?>
                    </div>
                    <form action="<?php echo SITE_URL; ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="card-deck">
                            <div class="card">
                                <div class="card-header">
                                    <h3>
                                        <?php echo gettext('accountinformation'); ?>
                                    </h3>
                                </div>
                                <div class="card-body">
                                <div class="form-group row">
                                    <label for="id" class="col-sm-4 col-form-label">
                                        <?php echo gettext('ID'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" title="id" name="id" value="<?php echo $user['id']; ?>" readonly/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="username" class="col-sm-4 col-form-label">
                                        <?php echo gettext('username'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" title="username" name="username" value="<?php echo $user['username']; ?>" readonly/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label">
                                        <?php echo gettext('email'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" title="email" name="email" value="<?php echo $user['email']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="birth_date" class="col-sm-4 col-form-label">
                                        <?php echo gettext('datebirth'); ?>
                                        <a href="https://www.ftc.gov/enforcement/rules/rulemaking-regulatory-reform-proceedings/childrens-online-privacy-protection-rule"
                                           target="_blank"
                                           rel="noopener"
                                           class="badge badge-danger">
                                            COPPA requirement
                                        </a>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control"
                                               title="<?php echo gettext('datebirth'); ?>"
                                               name="birth_date"
                                               placeholder="<?php echo $user['birth_date']; ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label">
                                        <?php echo gettext('password'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="password"
                                               class="form-control"
                                               id="changePassword"
                                               aria-describedby="passwordHelp"
                                               title="<?php echo gettext('password'); ?>"
                                               name="password"
                                               placeholder=""
                                        />
                                        <small id="passwordHelp" class="form-text text-muted">
                                            <?php echo gettext('blank'); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3>
                                    <?php echo gettext('socialinfo'); ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group row align-items-center">
                                    <label for="facebook_id" class="col-sm-3 col-form-label">
                                        <?php echo gettext('facebook'); ?>
                                    </label>
                                    <div class="col">
                                        <span class="input-group-prepend">
                                            <div class="input-group-text border-right-0">
                                                <?php echo gettext('facebook_link'); ?>
                                            </div>
                                            <input class="form-control" placeholder="Friendly Name" name="facebook_id"
                                                   value="<?php echo $user['facebook_id']; ?>"/>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="github" class="col-sm-3 col-form-label">
                                        <?php echo gettext('github_id'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" placeholder="Friendly Name" name="github_id"
                                               value="<?php echo $user['github_id']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="twitter" class="col-sm-3 col-form-label">
                                        <?php echo gettext('twitter'); ?>
                                    </label>
                                    <div class="col-sm-4">
                                        <span class="input-group-prepend">
                                            <div class="input-group-text border-right-0">@</div>
                                            <input type="text" class="form-control" id="twitter" placeholder="Friendly Name" name="twitter_id"
                                                   value="<?php echo $user['twitter_id']; ?>"/>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row text-center mt-4">
                                    <div class="col-sm-8">
                                        <a href="https://gravatar.com" class="btn btn-primary" target="_blank" rel="noopener">
                                            Change Gravatar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                    <input type='hidden' name='params' value='profile/edit/editdone'/>
                    <button class='btn btn-primary' value='<?php echo gettext('profileedit'); ?>'>
                        <?php echo gettext('submit'); ?>
                    </button>
                </form>
            <?php
                } else {
                    if ($params[0] === 'profile' && $params[2] === 'editdone') {
                        /* Update user profile entries */
                        Users::UpdateProfile();

                        /* Update Password if necessary */
                        if ($_POST['password'] != '') {
                            Users::userPasswordUpdateByID($_POST['id'], $_POST['password']);
                            Core::showSuccess(gettext('updatesuccess'));
                        }
                        Core::showSuccess(gettext('updatesuccess'));
                    } else {
                        Core::showError(gettext('error'));
                    }
                }
            }
        } ?>
    </div>
</div>
<script type="text/javascript" src="<?php echo JS_LAZYLOAD; ?>" integrity="<?php echo JS_LAZYLOAD_SRI;?>" crossorigin="anonymous" defer></script>
<!--suppress Annotator -->
<script>new LazyLoad();</script>