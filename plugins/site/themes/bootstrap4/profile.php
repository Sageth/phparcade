<?php
if (!isset($_SESSION)) {
    session_start();
}
global $params;
$dbconfig = PHPArcade\Core::getDBConfig(); ?>
<div class="card-block">
    <div class="col-lg-12 mt-4">
        <?php
        echo PHPArcade\Ads::getInstance()->showAds();
        if ($params[1] === 'view' && $params[1] != 'edit') {
            $user = PHPArcade\Users::getUserbyID($params[2]);
            if ($user === false) {
                PHPArcade\Core::showError(gettext('noexist'));
            } else {
                $games = PHPArcade\Games::getGamesChamp($user['id']); ?>
                <div class="col mt-4">
                    <img alt="<?php echo $user['username']; ?>'s Gravatar"
                         class="img img-responsive img-circle"
                         data-src="<?php echo PHPArcade\Users::userGetGravatar($user['username'], 80); ?>"
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
                                    <?php echo date('Y-m-d', $user['last_login']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <p class="card-text">
                                    <?php
                                    echo PHPArcade\Core::showGlyph('dashboard');
                echo "&nbsp;";
                echo gettext('activity'); ?>
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
                                if ($user['facebook_id'] != "") {
                                    ?>
                                    <a href="<?php echo URL_FACEBOOK . $user['facebook_id']; ?>" target="_blank" rel="noopener">
                                        <?php echo PHPArcade\Core::showGlyph('facebook', '2x', 'false'); ?>
                                    </a><?php
                                } else {
                                    echo PHPArcade\Core::showGlyph('facebook', '2x', 'false'); ?><?php
                                }

                if ($user['github_id'] != "") {
                    ?>
                                    <a href="<?php echo URL_GITHUB . $user['github_id']; ?>" rel="noopener"
                                       target="_blank">
                                        <?php echo PHPArcade\Core::showGlyph('github', '2x', 'false'); ?>
                                    </a><?php
                } else {
                    echo PHPArcade\Core::showGlyph('github', '2x', 'false');
                }

                if ($user['twitter_id'] != "") {
                    ?>
                                    <a href="<?php echo URL_TWITTER . $user['twitter_id']; ?>" rel="noopener"
                                       target="_blank">
                                        <?php echo PHPArcade\Core::showGlyph('twitter', '2x', 'false'); ?>
                                    </a><?php
                } else {
                    echo PHPArcade\Core::showGlyph('twitter', '2x', 'false');
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
                    $game = PHPArcade\Games::getGame($game['nameid']);
                    $link = PHPArcade\Core::getLinkGame($game['id']);
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
                                        <img alt="<?php echo gettext('play')
                                            . ' '
                                            . $game['name']
                                            . ' '
                                            . gettext('onlineforfree'); ?>"
                                             class="mx-auto d-block img-thumbnail img-rounded"
                                             data-src="<?php echo $img; ?>"
                                             height="<?php echo $dbconfig['theight']; ?>"
                                             title="<?php echo gettext('play')
                                                 . ' '
                                                 . $game['name']
                                                 . ' '
                                                 . gettext('onlineforfree'); ?>"
                                             width="<?php echo $dbconfig['twidth']; ?>"
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
                <script async type="application/ld+json">
                    {
                        "@context": "http://schema.org",
                        "@type": "Person",
                        "name": "<?php echo $user['username']; ?>",
                        "url": "<?php echo SITE_URL; ?>profile/view/<?php echo $user['id']; ?>/<?php echo $user['username']; ?>.html"
                        <?php
                        if (!empty($user['facebook_id']) || (!empty($user['github_id'])) || (!empty($user['twitter_id']))) {
                            ?>,
                            "sameAs": [
                                <?php if (!empty($user['facebook_id'])) {
                                ?>
                                    "http://www.facebook.com/<?php echo $user['facebook_id']; ?>",
                                    <?php
                            }

                            if (!empty($user['github_id'])) {
                                ?>
                                    "http://www.github.com/<?php echo $user['github_id']; ?>",
                                    <?php
                            }

                            if (!empty($user['twitter_id'])) {
                                ?>
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
                $user = PHPArcade\Users::getUserbyID($_SESSION['user']['id']);
                if ($params[2] == "" || !isset($params[2])) {
                    ?>
                    <div class="card border-0 mt-4">
                        <?php PHPArcade\Core::showInfo('Change your avatar at <a href="https://gravatar.com" target="_blank" rel="noopener">Gravatar.com</a>'); ?>
                    </div>
                    <form action="<?php echo SITE_URL; ?>" autocomplete="off" enctype="multipart/form-data"
                          method="POST">
                        <div class="card-deck">
                            <div class="card">
                                <div class="card-header">
                                    <h3>
                                        <?php echo gettext('accountinformation'); ?>
                                    </h3>
                                </div>
                                <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="id">
                                        <?php echo gettext('ID'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="id" readonly title="id"
                                               type="text" value="<?php echo $user['id']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="username" class="col-sm-4 col-form-label">
                                        <?php echo gettext('username'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="username" readonly title="username"
                                               type="text" value="<?php echo $user['username']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="email">
                                        <?php echo gettext('email'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="email" title="email" type="email"
                                               value="<?php echo $user['email']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="birth_date" class="col-sm-4 col-form-label">
                                        <?php echo gettext('datebirth'); ?>
                                        <a class="badge badge-danger"
                                           href="https://www.ftc.gov/enforcement/rules/rulemaking-regulatory-reform-proceedings/childrens-online-privacy-protection-rule"
                                           rel="noopener"
                                           target="_blank">
                                            COPPA requirement
                                        </a>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control"
                                               name="birth_date"
                                               placeholder="<?php echo $user['birth_date']; ?>"
                                               title="<?php echo gettext('datebirth'); ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="email">
                                        <?php echo gettext('password'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input aria-describedby="passwordHelp"
                                               class="form-control"
                                               id="changePassword"
                                               name="password"
                                               placeholder=""
                                               title="<?php echo gettext('password'); ?>"
                                               type="password"
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
                                    <label class="col-sm-3 col-form-label" for="facebook_id">
                                        <?php echo gettext('facebook'); ?>
                                    </label>
                                    <div class="col">
                                        <span class="input-group-prepend">
                                            <div class="input-group-text border-right-0">
                                                <?php echo gettext('facebook_link'); ?>
                                            </div>
                                            <input class="form-control" name="facebook_id" placeholder="Friendly Name"
                                                   value="<?php echo $user['facebook_id']; ?>"/>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="github">
                                        <?php echo gettext('github_id'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="github_id" placeholder="Friendly Name"
                                               value="<?php echo $user['github_id']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="twitter">
                                        <?php echo gettext('twitter'); ?>
                                    </label>
                                    <div class="col-sm-4">
                                        <span class="input-group-prepend">
                                            <div class="input-group-text border-right-0">@</div>
                                            <input class="form-control" id="twitter" name="twitter_id"
                                                   placeholder="Friendly Name" type="text"
                                                   value="<?php echo $user['twitter_id']; ?>"/>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row text-center mt-4">
                                    <div class="col-sm-8">
                                        <a class="btn btn-primary" href="https://gravatar.com" rel="noopener"
                                           target="_blank">
                                            Change Gravatar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                    <input name='params' type='hidden' value='profile/edit/editdone'/>
                    <button class='btn btn-primary' value='<?php echo gettext('profileedit'); ?>'>
                        <?php echo gettext('submit'); ?>
                    </button>
                </form>
            <?php
                } else {
                    if ($params[0] === 'profile' && $params[2] === 'editdone') {
                        /* Update user profile entries */
                        PHPArcade\Users::UpdateProfile();

                        /* Update Password if necessary */
                        if ($_POST['password'] != '') {
                            PHPArcade\Users::userPasswordUpdateByID($_POST['id'], $_POST['password']);
                            PHPArcade\Core::showSuccess(gettext('updatesuccess'));
                        }
                        PHPArcade\Core::showSuccess(gettext('updatesuccess'));
                    } else {
                        PHPArcade\Core::showError(gettext('error'));
                    }
                }
            }
        } ?>
    </div>
</div>
