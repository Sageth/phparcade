<script>
    (function() {
        const cx = 'partner-pub-<?php echo PHPArcade\Search::getGoogleSearchID();?>';
        const gcse = document.createElement('script');
        gcse.type = 'text/javascript';
        gcse.async = true;
        gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
        const s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(gcse, s);
    })();
</script>
<gcse:search></gcse:search>
