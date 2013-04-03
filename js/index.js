var adminMode = false;
var adminLoaded = false;
function autoLogin(func) {
	$.get("./data/admin/auto-login.php", function(res) {
		if (res) {
			loadAdminJs(function() {
				administer();
			});
		}
		func();
	});
}


$(document).ready(function() {
	$(".login").click(function() {
		loadAdminJs(function() {
			showLoginForm();
		});
		return false;
	});

	$("#tags-li").load("./data/tags.php");
	autoLogin(function() {
		loadByUrl();
	});
	setTimeout(function() {
		window.addEventListener('popstate', function(ev) {
			loadByUrl();
		}, false);
	}, 100);
});

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

function loadByUrl(scr) {
	var vars = getUrlVars();
	if (vars[0] == "p") {
		articleLoadNoEffect('?p=' + vars['p']);
		appendThumbs();
		tagSearchClose();
	} else if (vars[0] == "tag") {
		articleClose();
		appendThumbs();
		tagSearchLoad('?tag=' + vars['tag']);
	} else if (vars[0] == "author") {
		articleClose();
		appendThumbs();
		$("#thumbs").hide();
		tagSearchLoad('?author=' + vars['author']);
	} else if (vars[0] == "admin") {
		loadAdminJs(function() {
			articleLoad(window.location.search, scr);
		});

		appendThumbs();
		tagSearchClose();
	} else if (vars[0] == "ajax") {
		articleLoadNoEffect(window.location.search);
		appendThumbs();
		tagSearchClose();
	} else {
		reset();
		appendThumbs();
	}
}


$(window).resize(function() {
});

function changeSpan() {
	$(".thu2, .thu3, .thu4, .thu5").each(function() {
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
		var url = './data/thumbnails.php?offset=' + thumbsLoaded + '&limit=' + loadOnce;
		$("div.thumbs-buf").load(url, function() {
			$(this).appendTo("div#thumbs").fadeIn('normal', function() {
				$(this).removeClass("thumbs-buf");
				if (its)
					$(its).remove();
				thumbsLoaded += loadOnce;
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
	var scrollHeight = $(document).height();
	var scrollPosition = $(window).height() + $(window).scrollTop();
	if (scrollHeight - scrollPosition <= 50) {
		appendThumbs();
	}
}


$(window).bind("scroll", function() {
	loadIfYouInBottom();
});

$("#menu-toggle").click(function() {
	$("#menu").slideToggle();
});

function showAlert(msg) {
	$("div#alert-div").prepend("<div>" + msg + "</div>");
	$("div#alert-div").show();
	$("div#alert-div").queue(function() {
		setTimeout(function() {
			$("div#alert-div").dequeue();
		}, 5000)
	});
	$("div#alert-div").fadeOut("slow", function() {
		$(this).html("");
	});
}

function removeImgHeight() {
	$("#article img").each(function() {
		$(this).css("height", "auto");
	});
}

function changeTitle() {
	var siteName = $("#site-name").text();
	var pageTitle = $(".p-title").text();
	var divider = " : ";
	if (pageTitle == "") {
		divider = "";
	}
	$("title").text(pageTitle + divider + siteName);
}

function changeTitleTop() {
	var siteName = $("#site-name").text();
	$("title").text(siteName);
}

function socialButtonReload() {
	if ("twttr" in window) {
		$(".twitter-share-button").attr("data-url", location.href);
		$(".twitter-share-button").attr("data-text", document.title);
		twttr.widgets.load();
	}
	var fb_code = '<iframe src="http://www.facebook.com/widgets/like.php?href=' + encodeURIComponent(location.href) + '&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:70px; height:61px;" allowTransparency="true"></iframe>';
	$('#like-button').html(fb_code);
}

function socialButtonLoad() {
	$("div#social-buttons").load("./data/social-buttons.php", function() {
		socialButtonReload();
	});

}

function getRealUrl(url) {
	var realUrl = url;
	var vars = getSplit(url);
	if (vars[0] == "p")
		realUrl = 'article.php' + url;
	if (vars[0] == "admin")
		realUrl = 'admin.php' + url;
	if (vars[0] == "ajax") {
		realUrl = url.replace("?ajax=", "");
	}
	return realUrl;
}

function articleLoadNoEffect(url) {
	var realUrl = getRealUrl(url);
	$("#article").show();
	$("#article").load(realUrl, function() {
		$("#anim").css({
			"height" : ""
		});
		changeTitle();
		socialButtonLoad();
	});

}

function articleLoad(url, func) {
	var realUrl = getRealUrl(url);
	$("#anim").show();
	$("#anim").css("height", $("#article").height());
	$("#article").fadeOut("fast", function() {
		$(this).load(realUrl, function() {
			if (func == 'push') {
				history.pushState(null, null, url);
			}
			changeTitle();
			removeImgHeight();
			if (adminMode) {
				adminArticle(url);
			}
			if ($.isFunction(func)) {
				func();
			}
			$(this).fadeIn("fast");
			$("#anim").animate({
				'height' : $("#article").outerHeight()
			}, function() {
				$("#anim").animate({
					'height' : $("#article").outerHeight()
				}, function() {
					$("#anim").css({
						"height" : ""
					});
					socialButtonLoad();
				});
			});
			if (func != 'noscroll') {
				var scrollTo;
				if (adminMode && $(window).width() >= 768) {
					scrollTo = $("#article").offset().top - 40;
				} else {
					scrollTo = $("#article").offset().top - 10;
				}
				$("html, body").animate({
					scrollTop : scrollTo
				});
			}
		});
	});

}

function thumbsReset(func) {
	$("#thumbs").fadeOut("fast", function() {
		$(this).empty().queue(function() {
			thumbsLoaded = 2;
			nowLoading = false;
			appendThumbs();
			$(this).dequeue();
		});

		$(this).fadeIn("fast");
		if ($.isFunction(func)) {
			func();
		}
	})
}

function articleClose(func) {

	$("#article").hide('slow', function() {
		$("#article").html('');
		$("#article").empty();
		if ( typeof (func) == "function") {
			func();
		}
	});
	$("#anim").slideUp();
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
	if (push) {
		history.pushState(null, null, './');
		changeTitleTop();
	}

	$("#tags-li").load("./data/tags.php");
}


$("body").on("click", "a.reset", function() {
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
	if (url.indexOf("?tag") == 0) {
		url = 'data/tag-search.php' + url;
	} else if (url.indexOf("?author") == 0) {
		url = 'data/author-search.php' + url;
	}

	$("div#tag-search").slideUp(function() {
		$("div#tag-search").load(url, function() {
			$(this).slideDown("fast");
			changeTitle();
		});
	});

}

function closeTagSearch() {
	$("div#tag-search").slideUp(function() {
		history.pushState(null, null, './');
		$("div#tag-search").empty();
		changeTitle();
		thumbsOpen();
	});
	$("li.active").removeClass("active");

}


$("body").on("click", "a.ajaxtags", function() {
	$("li.active").removeClass("active");
	$(this).parent().toggleClass("active");
	var thishref = $(this).attr("href");
	articleClose(function() {
		history.pushState(null, null, thishref);
		tagSearchLoad(thishref);
	});
	$("div#thumbs").slideUp();
	return false;
});

function thumbsOpen() {
	$("div#thumbs").fadeIn('normal', function() {
	});
}


$("body").on("click", "button#closeTagSearch", function() {
	closeTagSearch();
	return false;
});

function loadAdminJs(func) {
	if (adminLoaded == false) {
		adminLoaded = true;
		$.getScript('./js/admin.js', function() {
			func();
		});
	} else {
		func();
	}
}

function loadNav() {
	$('#nav').fadeOut(function() {
		$(this).load("./data/nav/show.php", function() {
			$(this).fadeIn();
		});
	});
}

function loadArticleFooter() {
	var arr = getUrlVars();
	$("div.ar-footer").load("./data/article-footer.php?p=" + arr['p']);
}

function commentForm(arr, path) {
	$.post(path, arr.serializeArray(), function(res) {
		if (res.indexOf("OK") == 0) {
			loadArticleFooter();
		}
		showAlert(res);
	});
}


$("body").on("click", ".commentform input[type=submit]", function() {
	var arr = $('.commentform :input');
	var path = $(this).closest(".commentform").attr("action");
	commentForm(arr, path);
	return false;
});
