function deleteArticle(delid) {
	$.post("./data/delete-article.php", {
		'id' : delid,
	}, function(res) {
		reset();
		showAlert(res);
	}, 'text');
	$("#" + delid).hide().remove();
	history.pushState(null, null, './');
}

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
			if (newEdit) {
				prependThumbs();
			} else {
				$(".title" + $("input[name=rowid]").val()).html($("input[name=title]").val());
				$(".tag" + $("input[name=rowid]").val()).html($("input[name=tag]").val());
			}
			reset('push');
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
function loadEditor(func) {
	var thishref = './data/edit-article.html';
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

function replaceEditor(id) {
	$.post("./data/json-article.php", {
		id : id
	}, function(res) {
		var dotpos = res['headimage'].indexOf('.');
		var img_mini = res['headimage'].substring(0, dotpos) + 'x320' + res['headimage'].substring(dotpos);
		$('h1#editor-title').html("記事の編集");
		$('#edit-thumb').attr("src", 'data/' + img_mini);
		$('#postform input[name=headimage]').attr("value", res['headimage']);
		$("#postform input[name=title]").val(res['title']);
		$("#postform input[name=tag]").val(res['tag']);
		$("#postform input[name=rowid]").val(id);
		if ( typeof CKEDITOR == undefined) {
			$("#postform [name=body]").val(res['body']);
		} else {
			setTimeout(function() {
				CKEDITOR.instances.editor.setData(res['body']);
			}, 1000);
		}
	}, 'json');
}

var newEdit = true;
function openNew() {
	loadEditor();
	newEdit = true;
}

function openEdit(rowid) {
	alert(rowid);
	loadEditor(function() {
		replaceEditor(rowid);
		newEdit = false;
	});
}