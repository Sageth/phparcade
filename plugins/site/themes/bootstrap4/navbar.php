<?php if (!isset($_SESSION)) {
    session_start();
} ?>
<!-- Nav Section -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark pr-5">
    <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
        <?php echo gettext('logo'); ?>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <div class="col-lg-4 ml-auto">
                <?php include_once INST_DIR . 'includes/js/Google/googlecustomsearch.php';?>
            </div><?php
            if (!Users::isUserLoggedIn()) { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo gettext('gamecategories'); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarCategories">
                        <?php include_once __DIR__ . '/categoriesmenu.php'; ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo Core::getLinkRegister(); ?>" title="<?php echo gettext('login'); ?>" class="signupbutton">
                        <?php echo gettext('login'); ?>
                    </a>
                </li><?php
            } else {
                include_once __DIR__ . '/navbar-dropdown.php';
            } ?>
        </ul>
    </div>
</nav>
<!--End Nav Section -->