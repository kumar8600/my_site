<style>
	.nav-calender-ajax:hover {
		background-color: #eeeeee;
	}
	.exist {
		background-color: #EEEEEE;
	}
	.exist:hover {
		background-color: #FFFFFF;		
	}
	.exist a {
		color: #39C;
	}
</style>
<div class="dark">
<legend>カレンダー</legend>
<div class="nav-calender"></div>
</div>
<script type="text/javascript">
		$(".nav-calender").load("./data/plugins/nav/<?php echo basename(dirname(__FILE__)); ?>/data.php");
		$("body").on("click", ".nav-calender-ajax", function() {
			var url = $(this).attr("href");
			$(".nav-calender").load(url);
			return false;
		});
</script>