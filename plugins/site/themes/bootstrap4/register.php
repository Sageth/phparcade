<?php $dbconfig = PHPArcade\Core::getDBConfig(); ?>
<div class="card-deck mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Login</h2>
        </div>
        <div class="card-body">
            <form class="form-signin" autocomplete="off" action="<?php echo SITE_URL; ?>" method="post">
                <div class="form-label-group">
                    <input type="text" id="loginUsername" name="username" class="form-control" placeholder="User name" required autofocus />
                    <label for="loginUsername">Username</label>
                </div>
                <div class="form-label-group">
                    <input type="password" id="loginPassword" name="password" class="form-control" placeholder="Password" required />
                    <label for="loginPassword">Password</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" name="params" value="login/login">
                    <?php echo gettext('login'); ?>
                </button>
            </form>
        </div>
        <div class="card-footer">
            &nbsp;
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2><?php echo gettext('passwordrecovery');?></h2>
        </div>
        <?php PHPArcade\Core::showWarning(gettext('recoveryinstructions')); ?>
        <div class="card-body">
            <form class="form-signin" autocomplete="off" action="<?php echo SITE_URL; ?>" method="post">
                <div class="form-label-group">
                    <input class="form-control" id="forgotUsername" name="username" placeholder="User name">
                    <label for="forgotUsername"><?php echo gettext('username');?></label>
                </div>
                <div class="form-label-group">
                    <input class="form-control" id="forgotEmail" name="email" placeholder="Email Address">
                    <label for="forgotEmail"><?php echo gettext('email');?></label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" name="params" value="login/recover/do">
                    <?php echo gettext('recoverpassword'); ?>
                </button>
            </form>
        </div>
        <div class="card-footer">
            &nbsp;
        </div>
    </div>
</div>

<div class="card-block mt-4">
    <div class="card">
        <div class="card-header">
            <h2>
                <?php echo gettext('register');?>
            </h2>
        </div>
        <div class="card-body">
            <?php $execstatus = $execstatus ?? '';
            if ($execstatus == 'success') {
                /** @noinspection PhpUndefinedVariableInspection */
                switch ($status) {
                    case '':
                        echo 'eek';
                        break;
                    case 'confirmed':
                        PHPArcade\Core::doEvent('register_confirm');
                        PHPArcade\Core::showSuccess(gettext('registerconfirm'));
                        break;
                    case 'emailconf':
                        PHPArcade\Core::showSuccess(gettext('emailconf'));
                        break;
                    case 'passwordchangedsent':
                        PHPArcade\Core::showSuccess(gettext('passwordchangedsent'));
                        break;
                    case 'recoveryemailsent':
                        PHPArcade\Core::showSuccess(gettext('recoveryemailsent'));
                        break;
                    case 'emailinvalid':
                        PHPArcade\Core::showError(gettext('emailinvaliderror'));
                        break;
                    case 'generic':
                        PHPArcade\Core::showError(gettext('genericerror'));
                        break;
                    case 'notallfields':
                        PHPArcade\Core::showWarning(gettext('allfieldserror'));
                        break;
                    case 'usertaken':
                        PHPArcade\Core::showWarning(gettext('usertaken'));
                        break;
                    default:
                }
            } ?>
            <form class="form-signin" autocomplete="off" action="<?php echo SITE_URL; ?>" method="post">
                <div class="form-label-group">
                    <input type="text" id="registerUsername" name="username" class="form-control" placeholder="User name">
                    <label for="registerUsername"><?php echo gettext('username');?></label>
                </div>
                <div class="form-label-group">
                    <input type="email" id="registerEmail" name="email" class="form-control" placeholder="Email Address">
                    <label for="registerEmail"><?php echo gettext('email');?></label>
                </div>
                <div class="g-recaptcha form-label-group" data-sitekey="<?php echo $dbconfig['google_recaptcha_sitekey'];?>"></div>

                <!-- CloudFlare Server-side Exclude hides the registration button for suspicious visitors -->
                <!--sse-->
                <button class="btn btn-lg btn-dark btn-block" name="params" value="register/regdone">
                    <?php echo gettext('register'); ?>
                </button>
                <!--/sse-->
            </form>
        </div>
        <div class="card-footer">
            <p class="form-text text-muted">
                <?php echo gettext('validemail'); ?>
            </p>
        </div>
    </div>
</div>