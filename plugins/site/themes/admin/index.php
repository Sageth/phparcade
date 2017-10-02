<?php
Core::stopDirectAccess();
if (!isset($_SESSION)) {
    session_start();
}
global $links, $linkshref, $sublinkshref, $sublinks;
$content = $content ?? ''; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="<?php echo CHARSET; ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php
            echo gettext('logo');
            echo gettext('admin'); ?>
        </title>
        <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP; ?>"/>
        <link rel="stylesheet" href="<?php echo CSS_BOOTSTRAP_TOGGLE; ?>"/>
        <link rel="stylesheet" href="<?php echo CSS_SB_ADMIN_2; ?>" integrity="<?php echo CSS_SB_ADMIN_2_SRI;?>" crossorigin="anonymous"/>
        <link rel="stylesheet" href="<?php echo CSS_METISMENU; ?>"/>
        <link rel="stylesheet" href="<?php echo CSS_FONTAWESOME; ?>"/>
        <link rel="stylesheet" href="<?php echo CSS_INPUTCOLORS;?>"/>
    </head>
    <body>
        <div class="container-fluid">
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0;">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"><?php echo gettext('logo'); ?></a>
                </div>
                <!-- /.navbar-header -->
                <ul class="nav navbar-top-links navbar-right">
                    <!-- /.dropdown -->
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <?php echo Core::showGlyph('user'); ?><?php echo Core::showGlyph('caret-down'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li class="divider"></li>
                            <li>
                                <a href="<?php echo SITE_URL . 'index.php?params=login/logout'; ?>">
                                    <?php echo Core::showGlyph('sign-out'); ?>
                                    <?php echo gettext('logout'); ?>
                                </a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->
                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li class="sidebar-search">
                                <fieldset disabled>
                                    <div class="input-group custom-search-form">
                                        <input class="form-control" placeholder="Search...">
                                        <span class="input-group-btn">
											<button class="btn btn-default" type="button">
												<?php echo Core::showGlyph('search'); ?>
											</button>
										</span>
                                    </div><!-- /input-group -->
                                </fieldset>
                            </li>
                            <li>
                                <a href="index.php">
                                    <?php echo Core::showGlyph('dashboard'); ?>
                                    &nbsp;
                                    <?php echo gettext('dashboard'); ?>
                                </a>
                            </li><?php
                            if (!isset($_REQUEST['act'])) {
                                $_REQUEST['act'] = 'site';
                            }
                            for ($i = 0; $i < count($links); ++$i) {
                                $link = $links[$i];
                                $jstext =
                                    strtolower(preg_replace("/\-|\/|\s/", "", $link)); //If chars '-', '/', or " ";
                                if ($jstext === 'gamesmedia') {
                                    $jstext = 'media';
                                } ?>
                                <li>
                                    <a href="#">
                                        <?php echo Core::showGlyph('wrench'); ?>
                                        <?php echo $link; ?>
                                        <span class="fa arrow"></span>
                                    </a>
                                    <ul class="nav nav-second-level"><?php
                                        $snum = count($sublinks[$jstext]);
                                        for ($b = 0; $b < $snum; ++$b) {
                                            $link = $sublinks[$jstext][$b];
                                            $href = $sublinkshref[$jstext][$b]; ?>
                                                <li>
                                                    <a href="<?php echo $href; ?>">
                                                        <?php echo $link; ?>
                                                    </a>
                                                </li><?php
                                        } ?>
                                    </ul>
                                </li><?php
                            } ?>
                        </ul>
                    </div><!-- /.sidebar-collapse -->
                </div><!-- /.navbar-static-side -->
            </nav>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><?php echo gettext('logo'); ?> <?php echo gettext('admin'); ?></h1>
                    </div><!-- /.col-lg-12 -->
                </div><!-- /.row -->
                <div class="row">
                    <?php echo $content; ?>
                </div>
            </div><!-- /#page-wrapper -->
        </div><!-- /#wrapper -->
        <script src="<?php echo JS_JQUERY; ?>" defer></script>
        <script src="<?php echo JS_JQUERY_UI; ?>" defer></script>
        <script src="<?php echo JS_TABLESORT; ?>" defer></script>
        <script src="<?php echo JS_BOOTSTRAP; ?>" defer></script>
        <script src="<?php echo JS_BOOTSTRAP_TOGGLE; ?>" integrity="<?php echo JS_BOOTSTRAP_TOGGLE_SRI;?>" crossorigin="anonymous" defer></script>
        <script src="<?php echo JS_SB_ADMIN_2; ?>" integrity="<?php echo JS_SB_ADMIN_2_SRI;?>" crossorigin="anonymous" defer></script>
        <script src="<?php echo JS_METISMENU; ?>" defer></script>
    </body>
</html>