<?php
if (!isset($_SESSION)) {
    session_start();
}

global $params;
$dbconfig = \PHPArcade\Core::getDBConfig(); ?>
<div class="col-lg-12">
	<div class="card card text-white bg-info">
		<div class="card-header">
			<h1 class="card-title"><?php echo $dbconfig['sitetitle']; ?></h1>
		</div>
		<div class="card-body"><?php
            if ($params[1] === 'login') {
                if ($params[2] === 'success') {
                    header('Location: //' . $_SERVER['SERVER_NAME']);
                    PHPArcade\Core::loadRedirect(gettext('loggedin'));
                } elseif ($params[2] === 'wrongup') {
                    header('Location: ' . SITE_URL . '/register/register.html');
                } else {
                    header('Location: //' . $_SERVER['SERVER_NAME']);
                }
            } elseif ($params[1] === 'logout') {
                PHPArcade\Users::userSessionEnd();
            } elseif ($params[1] === 'recover') {
                if ($params[2] === "" || empty($params[2]) || !isset($params[2])) {
                    PHPArcade\Users::passwordRecoveryForm(); ?><br/>
					<?php echo gettext('emailwillbesentpw');
                } elseif ($params[2] === 'do') {
                    PHPArcade\Users::passwordRecovery();
                }
            } ?>
		</div>
	</div>
</div>