var adminMode = false;
var adminLoaded = false;
var loadIndicator = '<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>';
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
	History.Adapter.bind(window, 'statechange', function() {
		loadByUrl();
		//var State = History.getState();
		// Note: We are using History.getState() instead of event.state
		//History.log(State.data, State.title, State.url);
	});
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
		articleLoad(window.location.search);
	} else if (vars[0] == "tag") {
		tagSearchLoad(window.location.search);
	} else if (vars[0] == "author") {
		tagSearchLoad(window.location.search);
	} else if (vars[0] == "admin") {
		loadAdminJs(function() {
			articleLoad(window.location.search);
		});
	} else if (vars[0] == "ajax") {
		articleLoad(window.location.search);
	} else {
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
	allowToLoad = true;
	loadIfYouInBottom();
}

function resetThumbsLoad() {
	thumbsLoaded = 0;
	nowLoading = false;
	allowToLoad = false;
	allLoaded = false;
	$("#thumbs").fadeOut(function() {
		$("#thumbs").empty();
	});
}

var thumbsLoaded = 0;
var loadOnce = 1;
var nowLoading = false;
var allowToLoad = false;
function appendThumbs(force) {
	if ((nowLoading == false && allowToLoad == true) || (nowLoading == false && force === true)) {
		nowLoading = true;
		var url = './data/thumbnails.php';
		$.get(url, {
			"offset" : thumbsLoaded,
			"limit" : loadOnce
		}, function(res) {
			$("div#thumbs").append('<div class="hide" id="t' + thumbsLoaded + '">' + res.echo + '</div>');
			$("div#t" + thumbsLoaded).fadeIn(function() {
				if (allowToLoad == false) {
					return false;
				}
				socialButtonLoad();
				$(this).removeClass("thumbs-buf");
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
	if ($(window).width() >= 768) {
		$("#nav").css({
			marginLeft : ""
		});
		navShow = false;
	}
	if ($(window).width() < 768) {
		$("#nav").css({
			marginLeft : "-" + $("#nav").width()
		});
	}
});

var navShow = false;
function navToggle() {
	if (navShow) {
		$("#nav").animate({
			marginLeft : "-" + $("#nav").width(),
		}, 500);
	} else {
		$("#nav").animate({
			marginLeft : '',
		}, 500);
	}
	navShow = !navShow;
}


$("#nav-toggle").click(function() {
	navToggle();
});

function showHomeButton() {
	$("#home-button").fadeIn();
}

function hideHomeButton() {
	$("#home-button").fadeOut();
}

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
	//	if (viewingArticle == true) {
	hideHomeButton();
	articleClose(function() {
		$("#contents").animate({
		}, 20, function() {
			$("#contents").animate({
				marginLeft : '',
			}, 250, function() {
				$("#contents").fadeIn();
				$("#thumbs").show();
				if (func != "noLoad")
					startThumbsLoad();
				$("#contents").css({
					marginLeft : ''
				});
				if ( typeof (func) == "function") {
					func();
				}
			});

		})
	});

	viewingArticle = false;
	//	}

}

var viewingArticle = false;
function contentsLeft(func) {
	if (viewingArticle == false) {
		viewingArticle = true;
		resetThumbsLoad();
		showHomeButton();
		$("#article").animate({
			marginRight : '0',
		})
		$("#contents").animate({
			marginLeft : '-=' + $(window).width() + 'px'
		}, 500, function() {
			if ( typeof (func) == "function") {
				func();
			}
		})
		viewingArticle = true;
	}
}

function getRealUrl(url) {
	var realUrl = url;
	var vars = getSplit(url);
	if (vars[0] == "p") {
		realUrl = 'article.php' + url;
	} else if (vars[0] == "admin") {
		realUrl = 'admin.php' + url;
	} else if (vars[0] == "ajax") {
		realUrl = url.replace("?ajax=", "");
	} else if (vars[0] == "tag") {
		realUrl = 'data/tag-search.php' + url;
	} else if (vars[0] == "author") {
		realUrl = 'data/author-search.php' + url;
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
	$("#article").show("fast");
	contentsLeft(function() {
	});
	$("#article").html(loadIndicator);
	$("#article").load(realUrl, function() {
		if (func == 'push') {
			History.pushState(null, null, url);
		}
		changeTitle();
		removeImgHeight();
		if (adminMode) {
			adminArticle(url);
		}
		if ($.isFunction(func)) {
			func();
		}
		socialButtonLoad();

	});

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

function reset(push) {
	removeSearchContainers();
	if (push) {
		History.pushState(null, null, './');
		return true;
	}
	contentsReset();
	changeTitleTop();

	$("#tags-li").load("./data/tags.php");
}

function scrollTop() {
	var scrollTo;
	scrollTo = 30;
	$("html, body").animate({
		scrollTop : scrollTo
	}, 500);
}


$("body").on("click", "a.reset", function() {
	reset('push');
	return false;
});

$("body").on("click", ".ajax", function() {
	var thishref = $(this).attr("href");
	History.pushState(null, null, thishref);
	scrollTop();
	return false;
});

$("body").on("click", "a.ajaxtags", function() {
	var url = $(this).attr("href");
	var objective = this;
	if ($(objective).closest(".ar-thu-container").length == 0) {
		History.pushState(null, null, url);
	}
	if (!viewingArticle) {
		var realUrl = getRealUrl(url);
		$.get(realUrl, function(res) {
			$(objective).closest(".ar-thu-container").children(".search-container").slideUp(function() {
				$(this).remove();
			});
			$(objective).closest(".ar-thu-container").prepend(res);
			$(objective).closest(".ar-thu-container").children(".search-container").slideDown();
			if ($(document).scrollTop() > $(objective).closest(".ar-thu-container").offset().top) {
				$("html, body").animate({
					scrollTop : $(objective).closest(".ar-thu-container").offset().top
				}, 500);
			}

		});
	} else {
		History.pushState(null, null, url);
	}

	return false;
});

function removeSearchContainers() {
	var targets = $(".search-container");
	$(".search-container").slideUp(function() {
		$(targets).remove();
	});
}

function tagSearchLoad(url) {
	contentsReset("noLoad");
	url = getRealUrl(url);

	$.get(url, function(res) {
		removeSearchContainers();
		$("#thumbs").prepend(res);
		$(".search-container").slideDown();
		changeTitle();
	});

}


$("body").on("click", "button#closeTagSearch", function() {
	History.back();

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
