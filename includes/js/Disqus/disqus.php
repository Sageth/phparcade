<?php $currentlink = SITE_URL . trim($_SERVER['REQUEST_URI'], '/');
$dbconfig = Core::getDBConfig(); ?>
<!--suppress ALL -->
<div id="disqus_thread"></div>
<script data-cfasync="false">
    var disqus_config = function () {
        this.page.url = '<?php echo $currentlink;?>';
        this.page.identifier = '<?php echo $game['id'];?>';
    };
    (function() { // DON'T EDIT BELOW THIS LINE
        var d = document, s = d.createElement('script');
        s.src = 'https://<?php echo $dbconfig['disqus_user'];?>.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>