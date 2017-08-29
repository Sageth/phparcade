<?php
if (!isset($_SESSION)) {
    session_start();
}
$games = Games::getGames('all', 0, 3, '-all-', -1);
$i = 0; ?>
<!-- Carousel Section -->
<div id="main-Carousel" class="carousel slide">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<li data-target="#main-Carousel" data-slide-to="0" class="active"></li>
		<li data-target="#main-Carousel" data-slide-to="1" class=""></li>
		<li data-target="#main-Carousel" data-slide-to="2" class=""></li>
	</ol>

	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox"><?php
        foreach ($games as $game) {
            ++$i;
            $link = Core::getLinkGame($game['id']);
            if ($i === 1) {
                ?>
				<div class="item active"><?php
            } else {
                ?>
				<div class="item"><?php
            } ?>
			<div class="fill"></div>
				<div class="carousel-caption">
					<div class="thumbnail">
						<div class="caption">
							<h2><?php echo $game['name']; ?></h2>
							<p><?php echo $game['desc']; ?></p>
							<p>
								<a href="<?php echo $link; ?>" class="btn btn-danger">
									<?php echo gettext('playnow'); ?>
								</a>
							</p>
						</div>
					</div>
				</div>
			</div><?php
        } ?>
	</div>
	<!-- Controls -->
	<a class="left carousel-control" href="#main-Carousel" role="button" data-slide="prev">
		<span class="icon-prev"></span>
	</a>
	<a class="right carousel-control" href="#main-Carousel" role="button" data-slide="next">
        <span class="icon-next"></span>
	</a>
	<!--End Carousel Section -->
</div>