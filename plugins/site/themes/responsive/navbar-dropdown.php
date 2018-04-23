<?php
if (!isset($_SESSION)) {
    session_start();
} else {
    $user = $_SESSION['user'];
}
if (PHPArcade\Users::isUserLoggedIn()) {
    ?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php echo gettext('gamecategories'); ?> <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <?php include_once __DIR__ . '/categoriesmenu.php'; ?>
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php echo gettext('myaccount'); ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li class="dropdown-header">
                <img data-src="<?php echo PHPArcade\Users::userGetGravatar($user['name'], 25); ?>"
                     class="img img-responsive img-circle"
                     style="float:left"
                />&nbsp;
                <?php
                /** @noinspection PhpUndefinedVariableInspection */
                echo $user['name']; ?>
            </li><?php
            if ($user['admin'] === 'Yes') {
                ?>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo SITE_URL_ADMIN; ?>">
                        <?php echo gettext('admin'); ?>
                    </a>
                </li><?php
            } ?>
            <li class="divider"></li>
            <li class="dropdown-header">
                <?php echo gettext('profile'); ?>
            </li>
            <li>
                <a href='<?php echo PHPArcade\Core::getLinkProfile($user['id']); ?>'>
                    <?php echo gettext('myprofile'); ?>
                </a>
            </li>
            <li>
                <a href='<?php echo PHPArcade\Core::getLinkProfileEdit(); ?>'>
                    <?php echo gettext('profileedit'); ?>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href='<?php echo PHPArcade\Core::getLinkLogout(); ?>'>
                    <?php echo gettext('logout'); ?>
                </a>
            </li>
        </ul>
    </li><?php
}
