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
	}, 10);
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
		tagSearchClose();
	} else if (vars[0] == "tag") {
		articleClose();
		tagSearchLoad('?tag=' + vars['tag']);
	} else if (vars[0] == "author") {
		articleClose();
		$("#thumbs").hide();
		tagSearchLoad('?author=' + vars['author']);
	} else if (vars[0] == "admin") {
		loadAdminJs(function() {
			articleLoad(window.location.search, scr);
		});
		tagSearchClose();
	} else if (vars[0] == "ajax") {
		articleLoadNoEffect(window.location.search);
		tagSearchClose();
	} else {
		startThumbsLoad();
		reset();
	}
}

function changeSpan() {
	$(".thu2, .thu3, .thu4, .thu5").each(function() {
		if ($(this).position().top < $("#menu").position().top + $("#menu").outerHeight()) {
			$(this).removeClass("span6").addClass("span10");
		} else {
			$(this).removeClass("span10").addClass("span6");
		}
	});
}

function startThumbsLoad() {
	$("#contents").show();

	allowToLoad = true;
	loadIfYouInBottom();
}

function resetThumbsLoad() {
	$("#contents").hide()
	$("#thumbs").empty();
	thumbsLoaded = 0;
	nowLoading = false;
	allowToLoad = false;
}

var thumbsLoaded = 0;
var loadOnce = 1;
var nowLoading = false;
var allowToLoad = false;
function appendThumbs(its) {
	if (nowLoading == false && allowToLoad == true) {
		nowLoading = true;
		var url = './data/thumbnails.php';
		$.get(url, {
			"offset" : thumbsLoaded,
			"limit" : loadOnce
		}, function(res) {
			$("div#thumbs").append('<div class="hide" id="t' + thumbsLoaded + '">' + res.echo + '</div>');
			$("div#t" + thumbsLoaded).fadeIn(function() {
				socialButtonLoad();
				$(this).removeClass("thumbs-buf");
				if (its)
					$(its).remove();
				thumbsLoaded += loadOnce;
				allowToLoad = !res.end;
				nowLoading = false;
				loadIfYouInBottom()
			});
		}, "json");
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
	var scrollHeight = $("#contents").position().top + $("#contents").outerHeight();
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

$(window).resize(function() {
	if (navShow == false) {
		$("#nav").css({
			marginLeft : '0'
		});
	}
	if ($(window).width() < 768) {
		$("#nav").css({
			marginLeft : "-=" + $("#nav").width()
		});
	}
});

var navShow = false;
function navToggle() {
	if (navShow) {
		$("#nav").animate({
			marginLeft : "-=" + $("#nav").width(),
		}, 500);
	} else {
		$("#nav").animate({
			marginLeft : '0',
		}, 500);
	}
	navShow = !navShow;
}


$("#nav-toggle").click(function() {
	navToggle();
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

function socialButtonLoad() {
	if ("twttr" in window) {
		twttr.widgets.load();
		gapi.plusone.go();
	}
}

function contentsReset(func) {
	$("#contents").show();
	$("#contents").animate({
		marginLeft : 0,
	}, 500, function() {
		startThumbsLoad();
		if ( typeof (func) == "function") {
			func();
		}
	})
}

function contentsLeft(func) {
	$("#article").css({
		marginRight : '0',
	})
	$("#contents").animate({
		marginLeft : '-=' + $(window).width() + 'px',
		opacity : '0',
	}, 500, function() {
		$("#contents").css({
			opacity : '1',
		});
		resetThumbsLoad();
		if ( typeof (func) == "function") {
			func();
		}
	})
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
	contentsLeft();
	$("#article").load(realUrl, function() {
		changeTitle();
		socialButtonLoad();
		if (adminMode) {
			adminArticle(url);
		}
	});

}

function articleLoad(url, func) {
	var realUrl = getRealUrl(url);
	if (func != 'noscroll') {
		var scrollTo;
		scrollTo = 30;
		$("html, body").animate({
			scrollTop : scrollTo
		});
	}
	contentsLeft(function() {
		$("#article").load(realUrl, function() {
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
			socialButtonLoad();
		});
	});
}

function thumbsReset(func) {
	$("#thumbs").fadeOut("fast", function() {
		$(this).empty().queue(function() {
			thumbsLoaded = 0;
			allLoaded = false;
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
	$("#article").animate({
		marginRight : '-=' + $(window).width() + 'px',
		opacity : '0',
	}, 500, function() {
		$("#article").empty();
		$("#article").hide();
		$("#article").css({
			opacity : '1',
		});
		if ( typeof (func) == "function") {
			func();
		}
	});
}

function tagSearchClose() {
	$("div#tag-search").hide();
}

function reset(push) {
	articleClose();
	tagSearchClose();
	contentsReset();
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

$("body").on("click", ".ajax", function() {
	var thishref = $(this).attr("href");
	articleLoad(thishref, 'push');
	$(".viewing").removeClass("viewing");
	$(this).children().toggleClass("viewing");
	return false;
});

$("body").on("click", ".ajaxthumbs", function() {
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
	$("div.ar-footer").load("./data/article-footer.php" + window.location.search);
}

function loadArticleComments() {
	$("div.comments").load("./data/comment/list-comments.php" + window.location.search);
}

function commentForm(arr, path) {
	$.post(path, arr.serializeArray(), function(res) {
		if (res.indexOf("OK") == 0) {
			loadArticleComments();
			$("#inputBody").val("");
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
