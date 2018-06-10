<?php if (!isset($_SESSION)) {
    session_start();
} ?>
<!-- Nav Section -->
<nav class="navbar navbar-inverse navbar-static-top">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only"><?php echo gettext('togglenavigation'); ?></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo SITE_URL; ?>">
				<?php echo gettext('logo'); ?>
			</a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right"><?php
                if (!PHPArcade\Users::isUserLoggedIn()) {
                    ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php echo gettext('gamecategories'); ?> <span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<?php include_once __DIR__ . '/categoriesmenu.php'; ?>
						</ul>
					</li>
					<li class="active">
						<a href="<?php
                            echo PHPArcade\Core::getLinkRegister(); ?>" title="<?php echo gettext('login'); ?>" class="signupbutton">
							<?php echo gettext('login'); ?>
						</a>
					</li><?php
                } else {
                    include_once __DIR__ . '/navbar-dropdown.php';
                } ?>
			</ul>
			<div class="col-lg-4 navbar-right">
				<?php include_once INST_DIR . 'includes/js/Google/googlecustomsearch.php';?>
			</div>
			<!--<form class="navbar-form navbar-right visible-lg" role="search">
				<div class="form-group">
					<input type="text" name="q" id="q" class="form-control" placeholder="Search">
				</div>
				<button type="submit" name="params" value="search" class="btn btn-default"><?php //echo gettext("submit");?></button>
			</form>-->
		</div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container-fluid -->
</nav>
<!--End Nav Section -->