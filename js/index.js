$(document).ready(function() {
	$("#tags-li").load("./data/tags.php");
	phoneMenuHide();
	loadByUrl();
	setTimeout(function() {
		window.addEventListener('popstate', function(ev) {
			loadByUrl();
		}, false);
	}, 100);

});

function getUrlVars() {
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for (var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}

function loadByUrl(scr) {
	var vars = getUrlVars();
	if (vars['p'] !== undefined) {
		articleLoad('?p=' + vars['p'], scr);
		thumbsLoad("./data/thumbnails.php", function() {
			tagSearchClose();
		});
	} else if (vars['tag'] !== undefined) {
		articleClose();
		thumbsLoad("./data/thumbnails.php", function() {
			$("#thumbs").hide();
		});
		tagSearchLoad('?tag=' + vars['tag']);
	} else {
		reset();
		thumbsLoad("./data/thumbnails.php");
	}
}

function phoneMenuHide() {
	if ($(window).width() <= 767) {
		$("#menu").hide();
	} else {
		$("#menu").show();
	}
}


$(window).resize(function() {
	phoneMenuHide();
	changeSpan();
});

function changeSpan() {
	$(".thu").each(function() {
		if ($(this).position().top < $("#menu").position().top + $("#menu").outerHeight()) {
			$(this).removeClass("span6").addClass("span10");
		} else {
			$(this).removeClass("span10").addClass("span6");
		}
	});
}

var thumbsLoaded = 0;
var loadOnce = 6;
var nowLoading = false;
function appendThumbs(its) {
	if ($("div.thumbs-buf").hasClass("end")) {
		nowLoading = true;
		return false;
	}
	if (nowLoading == false) {
		nowLoading = true;
		thumbsLoaded = thumbsLoaded + loadOnce;
		var url = './data/thumbnails.php?offset=' + thumbsLoaded + '&limit=' + loadOnce;
		$("div.thumbs-buf").load(url, function() {
			$(this).appendTo("div#thumbs").fadeIn('normal', function() {
				$(this).removeClass("thumbs-buf");
				if (its)
					$(its).remove();
				nowLoading = false;
				loadIfYouInBottom()
			});
		});
	}
}

function prependThumbs() {
	var url = './data/thumbnails.php?offset=0' + '&limit=1';
	$("div.thumbs-buf").load(url, function() {
		$(this).prependTo("div#thumbs").fadeIn('normal', function() {
			$(this).removeClass("thumbs-buf");
			nowLoading = false;
		});
	});
}

function loadIfYouInBottom() {
	var contentBottom = $('#thumbs').offset().top + $('#thumbs').height();
	var scrollPosition = $(window).height() + $(window).scrollTop();
	if (contentBottom - scrollPosition <= 150) {
		appendThumbs();
	}
}


$(window).bind("scroll", function() {
	loadIfYouInBottom();
});

$("button#menu-toggle").click(function() {
	$("#menu").slideToggle();
});

function showAlert(msg) {
	$("div#alert-div").prepend('<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>' + msg + '</div>');
}

function removeImgHeight() {
	$("#article img").each(function() {
		$(this).css("height", "auto");
	});
}

function articleLoad(url, func) {
	$("#anim").show();
	$("#anim").css("height", $("#article").height());
	$("#article").fadeOut("fast", function() {
		if (func == 'push') {
			history.pushState(null, null, url);
		}
		if (url.indexOf('?p=') == 0)
			url = 'article.php' + url;
		$(this).load(url, function() {
			removeImgHeight();
			if ($.isFunction(func)) {
				func();
			}
			$(this).fadeIn("fast");
			$("#anim").animate({
				'height' : $("#article").height()
			}, function() {
				$("#anim").animate({
					'height' : $("#article").height()
				}, function() {
					$("#anim").css({
						"height" : ""
					});
					changeSpan();
				});
			});
			if (func != 'noscroll') {
				$("html, body").animate({
					scrollTop : $("#article").offset().top - 10
				});
			}
		});
	});

}

function thumbsLoad(url, func) {
	$("#thumbs").fadeOut("fast", function() {
		if (func == 'push') {
			history.pushState(null, null, url);
		}
		$(this).load(url, function() {
			$(this).fadeIn("fast");
			//$("#animthumbs").css("height", $("#thumbs").height());
			changeSpan();
			if ($.isFunction(func)) {
				func();
			}
		});
	});
}

function articleClose() {
	$("#anim").hide('slow', function() {
		$("#article").html('');
		changeSpan();
	});
}

function tagSearchClose() {
	$("div#tag-search").hide();
}

function reset(push) {
	articleClose();
	tagSearchClose();
	$("div#thumbs").fadeOut("fast", function() {
		thumbsOpen();
	});
	$(".active").removeClass("active");
	if (push)
		history.pushState(null, null, './');
	$("#tags-li").load("./data/tags.php");
}


$("a.reset").click(function() {
	reset('push');
	return false;
});

$("body").on("click", "a.ajax", function() {
	var thishref = $(this).attr("href");
	articleLoad(thishref, 'push');
	$(".viewing").removeClass("viewing");
	$(this).children().toggleClass("viewing");
	return false;
});

$("body").on("click", "a.ajaxthumbs", function() {
	var thishref = $(this).attr("href");
	articleClose();
	return false;
});

function tagSearchLoad(url) {
	url = 'data/tag-search.php' + url;
	$("div#tag-search").slideUp(function() {
		$("div#tag-search").load(url, function() {
			$(this).slideDown("fast");
			changeSpan();
		});
	});

}


$("body").on("click", "a.ajaxtags", function() {
	$("li.active").removeClass("active");
	$(this).parent().toggleClass("active");
	var thishref = $(this).attr("href");
	history.pushState(null, null, thishref);
	tagSearchLoad(thishref);
	articleClose();
	$("div#thumbs").slideUp();
	return false;
});

function thumbsOpen() {
	$("div#thumbs").fadeIn('normal', function() {
		changeSpan()
	});
}


$("body").on("click", "button#closeTagSearch", function() {
	$("div#tag-search").slideUp();
	thumbsOpen();
	$("li.active").removeClass("active");
	history.pushState(null, null, './');
	return false;
});

var adminLoaded = false;
function loadAdminJs(func) {
	if (adminLoaded == false) {
		$.getScript('./js/admin.js', function() {
			func();
		});
	} else {
		func();
	}
}


$("body").on("click", "button.new", function() {
	loadAdminJs(function() {
		openNew();
	});

	return false;
});

$("body").on("click", "button.edit", function() {
	var rowid = $(this).val();
	loadAdminJs(function() {
		openEdit(rowid);
	});

	return false;
});

$("body").on("click", "button.del", function() {
	loadAdminJs(function() {
		deleteArticle($(this).attr("href"));
	});

	return false;
});
