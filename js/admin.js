var session_user_id;
var session_user_name;
function getSessionUser() {
	if (session_user_id == null) {
		$.ajax({
			url : "./data/admin/json-user-session.php",
			dataType : "json",
			async : false,
			success : function(res) {
				session_user_id = res['userid'];
				session_user_name = res['name'];
			}
		})
		return session_user_id;
	}
	return session_user_id;
}

function removeSessionUser() {
	session_user_id = null;
	session_user_name = null;
}

function getSessionUserName() {
	if (session_user_name == null) {
		getSessionUser();
	}
	return session_user_name;
}

function administer() {
	loadAdminMenu(function() {
		adminMode = true;
		$(".login").hide();
		$("#loginform").hide();
		$("div.admin-menu").show();
		$("a.userid").html(getSessionUserName() + "(" + getSessionUser() + ")");
		$("a.userid").attr("href", "?author=" + getSessionUser());
		if (getSessionUser() == "root") {
			$(".root-only").show();
		} else {
			$(".root-only").hide();
		}
		hideAdminMenu();
	})
}

var loginFormLoaded = false;
function showLoginForm() {
	$("div#loginform").toggle();
	if (loginFormLoaded == false) {
		$("div#loginform").load("./data/admin/loginform.php", function() {
			$("#loginform input[type=submit]").click(function() {
				$.post("./data/admin/login.php", {
					'userid' : $("input[name=userid]").val(),
					'password' : $("input[name=password]").val()
				}, function(res) {
					if (res == true) {
						showAlert("ログイン成功");
						loadByUrl();
						administer();
					} else {
						showAlert("ログイン失敗");
					}
					$("#loginModal").modal('hide');
				});
				return false;
			});
		});
	}
}

function outAdminister() {
	adminMode = false;
	$(".login").show();
	$("div.admin-menu").hide();
	reset('push');
}

function logout() {
	$.get("./data/admin/logout.php", function(res) {
		if (res) {
			showAlert("ログアウトしました。");
			outAdminister();
			removeSessionUser();
		}
	})
}


$("a.logout").click(function() {
	logout();
	return false;
});

function adminArticle(url) {
	var id = url.substring(url.lastIndexOf('?p=') + 3);
	$("div.admin-article").load("./data/admin/admin-article.php", {
		id : id
	});
}

function deleteArticle(href) {
	$.get(href, function(res) {
		reset("push");
		showAlert(res);
	}, 'text');
	var delid = href.substring(href.lastIndexOf('id=') + 3);
	$("#" + delid).hide().remove();
}


$("body").on("click", "a.del", function() {
	var href = $(this).attr("href");
	deleteArticle(href);
	return false;
});

function reloadAdminMenu() {
	var res = getSessionUser();
	if (res) {
		administer();
	} else {
		outAdminister();
	}
}

function reloadHeader() {
	$("header.page-header").load("./data/header.php");
}

function envReset() {
	removeSessionUser();
	getSessionUser();
	reloadAdminMenu();
	contentsReset();
	loadNav();
}

function ajaxForm(arr, path) {
	$.post(path, arr.serializeArray(), function(res) {
		if (res.indexOf("OK") == 0) {
			reset("push");
			envReset();
		}
		if (res.indexOf("SUCCESS") == 0) {
			history.back();
		}
		showAlert(res);
		reloadHeader();
	});
}


$("body").on("click", ".ajaxform input[type=submit]", function() {
	var arr = $('.ajaxform :input');
	var path = $(this).closest(".ajaxform").attr("action");
	ajaxForm(arr, path);
	return false;
});

$("body").on("click", ".editorform input[type=submit]", function() {
	$("#editor").val(CKEDITOR.instances.editor.getData());

	var arr = $('.editorform :input');
	var path = $(this).closest(".editorform").attr("action");
	ajaxForm(arr, path);
	return false;
});

function loadAdminMenu(func) {
	$("#admin-menu-container").load("./data/admin/admin-menu.php", function() {
		if ( typeof (func) == "function") {
			func();
		}
	});
}

function hideAdminMenu() {
	$("#admin-menu").animate({
		marginBottom : "-" + $("#admin-config").outerHeight() + "px"
	});
	adminMenuShowing = false;
}

function showAdminMenu() {
	$("#admin-menu").animate({
		marginBottom : "0"
	});
	adminMenuShowing = true;
}

var adminMenuShowing = false;
function toggleAdminMenu() {
	if (adminMenuShowing) {
		hideAdminMenu();
	} else {
		showAdminMenu();
	}
}


$("body").on("click", ".config-toggle", function() {
	toggleAdminMenu();
	return false;
}); 