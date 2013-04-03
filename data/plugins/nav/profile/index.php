<div class="dark">
	<legend>
		プロフィール
	</legend>
	<div id="profile<?php echo $_GET['p']; ?>"></div>
<script type="text/javascript">
	function getSplit(url) {
		var vars = [], hash;
		var hashes = url.slice(url.indexOf('?') + 1).split('&');
		for (var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;	
	}	

	function getUrlVars() {
		return getSplit(window.location.href);
	}
	var url = window.location.href;
	function hrefChange(func) {
		if(url != window.location.href) {
			func();
		}
		url = window.location.href;
	}
	function reloadProfile() {
		var vars = getUrlVars();
		$.get("./data/plugins/nav/<?php echo basename(dirname(__FILE__)); ?>/profile.php" + window.location.search , function(res) {
			$("#profile<?php echo $_GET['p']; ?>").fadeOut(function() {
				$(this).html(res);
				$(this).fadeIn();
			});
		});
	}
	window.setInterval(function() {
		hrefChange(function() {
			reloadProfile();
		});
	}, 2000);
	window.onload = function() {
		reloadProfile();
	};
	
</script>
</div>
