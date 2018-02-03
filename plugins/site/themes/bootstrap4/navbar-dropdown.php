<?php
if (!isset($_SESSION)) {
    session_start();
} else {
    $user = $_SESSION['user'];
}
if (Users::isUserLoggedIn()) {
    ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo gettext('gamecategories'); ?>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarCategories">
            <?php include_once __DIR__ . '/categoriesmenu.php'; ?>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo gettext('myaccount'); ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li class="dropdown-header">
                <img data-original="<?php echo Users::userGetGravatar($user['name'], 25); ?>"
                     class="img img-fluid rounded-circle"
                     style="float:left"
                />&nbsp;
                <?php
                /** @noinspection PhpUndefinedVariableInspection */
                echo $user['name']; ?>
            </li><?php
            if ($user['admin'] === 'Yes') { ?>
                <li class="dropdown-divider"></li>
                <a class="dropdown-item" href="<?php echo SITE_URL_ADMIN; ?>">
                    <?php echo gettext('admin'); ?>
                </a><?php
            } ?>
            <li class="dropdown-divider"></li>
            <li class="dropdown-header">
                <?php echo gettext('profile'); ?>
            </li>
            <a class="dropdown-item" href='<?php echo Core::getLinkProfile($user['id']); ?>'>
                <?php echo gettext('myprofile'); ?>
            </a>
            <a class="dropdown-item" href='<?php echo Core::getLinkProfileEdit(); ?>'>
                <?php echo gettext('profileedit'); ?>
            </a>
            <li class="dropdown-divider"></li>
            <a class="dropdown-item" href='<?php echo Core::getLinkLogout(); ?>'>
                <?php echo gettext('logout'); ?>
            </a>
        </ul>
    </li><?php
}
