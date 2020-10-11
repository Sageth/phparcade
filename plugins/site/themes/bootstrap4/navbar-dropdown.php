<?php
if (!isset($_SESSION)) {
    session_start();
} else {
    $user = $_SESSION['user'];
}
if (PHPArcade\Users::isUserLoggedIn()) {
    ?>
    <li class="nav-item dropdown">
        <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"
           id="navbarCategories">
            <?php echo gettext('gamecategories'); ?>
        </a>
        <ul aria-labelledby="navbarCategories" class="dropdown-menu">
            <?php include_once __DIR__ . '/categoriesmenu.php'; ?>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarCategories" data-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false">
            <?php echo gettext('myaccount'); ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
            <li class="dropdown-header">
                <img alt="<?php $user['name'];?>'s Gravatar"
                     class="img img-fluid rounded-circle lazy"
                     data-src="<?php echo PHPArcade\Users::userGetGravatar($user['name'], 25); ?>"
                     style="float:left"
                />&nbsp;
                <?php
                /** @noinspection PhpUndefinedVariableInspection */
                echo $user['name']; ?>
            </li><?php
            if ($user['admin'] === 'Yes') {
                ?>
                <li class="dropdown-divider"></li>
                <a class="dropdown-item" href="<?php echo SITE_URL_ADMIN; ?>" target="_blank" rel="noopener">
                    <?php echo gettext('admin'); ?>
                </a><?php
            } ?>
            <li class="dropdown-divider"></li>
            <li class="dropdown-header">
                <?php echo gettext('profile'); ?>
            </li>
            <a class="dropdown-item" href='<?php echo PHPArcade\Core::getLinkProfile($user['id']); ?>'>
                <?php echo gettext('myprofile'); ?>
            </a>
            <a class="dropdown-item" href='<?php echo PHPArcade\Core::getLinkProfileEdit(); ?>'>
                <?php echo gettext('profileedit'); ?>
            </a>
            <li class="dropdown-divider"></li>
            <a class="dropdown-item" href='<?php echo PHPArcade\Core::getLinkLogout(); ?>'>
                <?php echo gettext('logout'); ?>
            </a>
        </ul>
    </li><?php
}
