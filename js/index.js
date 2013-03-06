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
	$(".t0, .t1, .t2, .t3, .t4, .t5").each(function() {
		if ($(this).position().top < $("#menu").position().top + $("#menu").height()) {
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
			});
		});
	}
}

function prependThumbs() {
	var url = './data/thumbnails.php?offset=0' + '&limit=1';
	$("div.thumbs-buf").load(url, function() {
		$(this).prependTo("div#thumbs").fadeIn('normal', function() {
			$(this).removeClass("thumbs-buf");
			if (its)
				$(its).remove();
			nowLoading = false;
		});
	});
}


$("body").on("click", "button.thu-more", function() {
	var its = this;
	appendThumbs(its);
});

$(window).bind("scroll", function() {
	var contentBottom = $('#thumbs').offset().top + $('#thumbs').height();
	var scrollPosition = $(window).height() + $(window).scrollTop();
	if (contentBottom - scrollPosition <= 150) {
		appendThumbs();
	}
});

$("button#menu-toggle").click(function() {
	$("#menu").slideToggle();
});

function showAlert(msg) {
	$("div#alert-div").prepend('<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>' + msg + '</div>');
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

function deleteArticle(delid) {
	$.post("./data/delete-article.php", {
		'id' : delid,
	}, function(res) {
		reset();
		showAlert(res);
	}, 'text');
}


$("body").on("click", "button.del", function() {
	deleteArticle($(this).attr("href"));
	return false;
});

function defineSubmit() {
	$("input[type=submit]").click(function() {
		var body = CKEDITOR.instances['editor'].getData();
		var chkvars = {
			'メインイメージ' : $("input[name=headimage]").val(),
			'タイトル' : $("input[name=title]").val(),
			'本文' : body,
			'タグ' : $("input[name=tag]").val(),
		}
		for (var i in chkvars) {
			if (chkvars[i] == '') {
				alert(i + 'を入力してください。');
				return false;
			}
		}
		$.post("./data/insert-article.php", {
			'headimage' : $("input[name=headimage]").val(),
			'title' : $("input[name=title]").val(),
			'body' : body,
			'tag' : $("input[name=tag]").val(),
			'rowid' : $("input[name=rowid]").val(),
		}, function(res) {
			showAlert(res);
			reset();
			prependThumbs();
		}, 'text');

		return false;
	});

	$('input[type=file]').change(function() {
		$(this).upload('./data/upload-image.php', function(res) {
			var dotpos = res['filename'].indexOf('.');
			var img_mini = res['filename'].substring(0, dotpos) + 'x320' + res['filename'].substring(dotpos);
			$('#thumb').html(res['error']);
			$('#edit-thumb').attr("src", 'data/' + img_mini);
			$('#postform [name=headimage]').attr("value", res['filename']);
		}, 'json');
	});
}

function loadEditorJs() {
	window.CKEDITOR_BASEPATH = './js/ckeditor/';
	$.getScript("./js/ckeditor/ckeditor.js", function() {
		CKEDITOR.replace('editor');
		CKEDITOR.on('instanceReady', function() {
			$("#anim").animate({
				'height' : $("#article").height()
			}, function() {
				$("#anim").css({
					"height" : ""
				});
			});
		});
	});

	$.getScript("./js/jquery.upload-1.0.2.min.js");

}

var editorLoaded = false;
function loadEditor(thishref, func) {
	if (editorLoaded == false) {
		articleLoad(thishref, function() {
			loadEditorJs();
			defineSubmit();
			if (func !== undefined)
				func();
		});
		editorLoaded = true;
	} else {
		articleLoad(thishref, function() {
			CKEDITOR.replace('editor');
			defineSubmit();
			if (func !== undefined)
				func();
		});
	}
}


$("body").on("click", "button.new", function() {
	loadEditor($(this).attr("href"));
	return false;
});

function replaceEditor(id) {
	$.post("./data/json-article.php", {
		id : id
	}, function(res) {
		var dotpos = res['headimage'].indexOf('.');
		var img_mini = res['headimage'].substring(0, dotpos) + 'x320' + res['headimage'].substring(dotpos);
		$('h1#editor-title').html("記事の編集");
		$('#edit-thumb').attr("src", 'data/' + img_mini);
		$('#postform [name=headimage]').attr("value", res['headimage']);
		$("#postform [name=title]").val(res['title']);
		$("#postform [name=tag]").val(res['tag']);
		$("#postform [name=rowid]").val(id);
		if ( typeof CKEDITOR != "undefined") {
			CKEDITOR.instances['editor'].setData(res['body']);
		} else {
			$("#postform [name=body]").val(res['body']);
		}
	}, 'json');
}


$("body").on("click", "button.edit", function() {
	var rowid = $(this).val();
	loadEditor($(this).attr("href"), function() {
		replaceEditor(rowid);
	});
	return false;
});

