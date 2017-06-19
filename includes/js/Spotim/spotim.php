<?php $dbconfig = Core::getDBConfig();?>
<script type="text/javascript" data-cfasync="false">
	!function (t, e, n) {
		function a(t) {
			var a = e.createElement("script");
            //noinspection CommaExpressionJS,JSUnresolvedVariable
            a.type = "text/javascript" , a.async = !0, a.src = ("https:" === e.location.protocol ? "https" : "http") + ":" + n, (t || e.body || e.head).appendChild(a)
		}

		function o() {
			var t = e.getElementsByTagName("script"), n = t[t.length - 1];
			return n.parentNode
		}

		var p = o();
		//noinspection CommaExpressionJS
		t.spotId = "<?php echo $dbconfig['spotim_id'];?>", t.parentElement = p, a(p)
	}(window.SPOTIM = {}, document, "//www.spot.im/launcher/bundle.js");
</script>