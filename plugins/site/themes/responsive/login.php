<?php
if (!isset($_SESSION)) {
    session_start();
}

global $params;
$dbconfig = Core::getInstance()->getDBConfig(); ?>
<div class="col-lg-12">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h1 class="panel-title"><?php echo $dbconfig['sitetitle']; ?></h1>
		</div>
		<div class="panel-body"><?php
            if ($params[1] === 'login') {
                if ($params[2] === 'success') {
                    header('Location: //' . $_SERVER['SERVER_NAME']);
                    Core::loadRedirect(gettext('loggedin'));
                } elseif ($params[2] === 'wrongup') {
                    //Core::loadRedirect(gettext("loginerror"));
                    header('Location: ' . SITE_URL . '/register/register.html');
                } else {
                    header('Location: //' . $_SERVER['SERVER_NAME']);
                }
            } elseif ($params[1] === 'logout') {
                Users::userSessionEnd();
            } elseif ($params[1] === 'recover') {
                if ($params[2] === "" || empty($params[2]) || !isset($params[2])) {
                    Users::passwordRecoveryForm(); ?><br/>
					<?php echo gettext('emailwillbesentpw');
                } elseif ($params[2] === 'do') {
                    Users::passwordRecovery();
                }
            } ?>
		</div>
	</div>
</div>