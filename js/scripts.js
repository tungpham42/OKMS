function postLike(pid) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_post.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		if (!data.includes("not_belonged") && !data.includes("is_admin") || (data.includes("guest_mode") && !data.includes("is_admin"))) {
			$("#save_post_like_pid_"+pid).load("/triggers/post_like.php",{pid:pid},function(){
				$("#post_like_pid_"+pid).load("/triggers/post_like_update.php",{pid:pid});
				$("#post_dislike_pid_"+pid).load("/triggers/post_dislike_update.php",{pid:pid});
			});
		} else if (data.includes("not_belonged")) {
			openWrap("You do not belong to this course");
		} else if (data.includes("is_admin")) {
			openWrap("Admin cannot like post");
		}
	});
}
function postDislike(pid) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_post.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		if (!data.includes("not_belonged") && !data.includes("is_admin") || (data.includes("guest_mode") && !data.includes("is_admin"))) {
			$("#save_post_dislike_pid_"+pid).load("/triggers/post_dislike.php",{pid:pid},function(){
				$("#post_dislike_pid_"+pid).load("/triggers/post_dislike_update.php",{pid:pid});
				$("#post_like_pid_"+pid).load("/triggers/post_like_update.php",{pid:pid});
			});
		} else if (data.includes("not_belonged")) {
			openWrap("You do not belong to this course");
		} else if (data.includes("is_admin")) {
			openWrap("Admin cannot dislike post");
		}
	});
}
function postFollow(pid) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_post.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		if (!data.includes("not_belonged") && !data.includes("is_admin") || (data.includes("guest_mode") && !data.includes("is_admin"))) {
			$("#save_post_follow_pid_"+pid).load("/triggers/post_follow.php",{pid:pid},function(){
				$("#post_follow_pid_"+pid).load("/triggers/post_follow_update.php",{pid:pid});
			});
		} else if (data.includes("not_belonged")) {
			openWrap("You do not belong to this course");
		} else if (data.includes("is_admin")) {
			openWrap("Admin cannot follow post");
		}
	});
}
function commentsToggle(pid) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_comments_count.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		if (data.includes("not_loggedin") && data.includes("no_comment")) {
			openLogin();
		} else {
			toggle_comments("comments_pid_"+pid);
		}
		if ($("comments_pid_"+pid).css("display") == "none") {
			$("#comments_count_pid_"+pid).removeClass("clicked");
		} else if ($("comments_pid_"+pid).css("display") == "block") {
			$("#comments_count_pid_"+pid).addClass("clicked");
		}
	});
}
function starRating(pid,rate) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_post.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		if (!data.includes("not_belonged") && !data.includes("is_admin") || (data.includes("guest_mode") && !data.includes("is_admin"))) {
			$("#save_post_rate_pid_"+pid).load("/triggers/post_rate.php",{pid:pid,rate:rate},function(){
				$("#post_rate_pid_"+pid).load("/triggers/post_rate_update.php",{pid:pid});
				$("#average_post_rate_pid_"+pid).load("/triggers/post_rate_average.php",{pid:pid});
			});
		} else if(data.includes("not_belonged")) {
			openWrap("You do not belong to this course");
		} else if(data.includes("is_admin")) {
			openWrap("Admin cannot rate");
		}
	});
}
function commentLike(comid,pid) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_post.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		if (!data.includes("is_admin") && !data.includes("not_belonged") || (data.includes("guest_mode") && !data.includes("is_admin"))) {
			$("#save_comment_like_comid_"+comid).load("/triggers/comment_like.php",{comid:comid},function(){
				$("#comment_like_comid_"+comid).load("/triggers/comment_like_update.php",{comid:comid});
				$("#comment_dislike_comid_"+comid).load("/triggers/comment_dislike_update.php",{comid:comid});
			});
		} else if (data.includes("not_belonged")) {
			openWrap("You do not belong to this course");
		} else if (data.includes("is_admin")) {
			openWrap("Admin cannot like comment");
		}
	});
}
function commentDislike(comid,pid) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_post.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		if (!data.includes("is_admin") && !data.includes("not_belonged") || (data.includes("guest_mode") && !data.includes("is_admin"))) {
			$("#save_comment_dislike_comid_"+comid).load("/triggers/comment_dislike.php",{comid:comid},function(){
				$("#comment_dislike_comid_"+comid).load("/triggers/comment_dislike_update.php",{comid:comid});
				$("#comment_like_comid_"+comid).load("/triggers/comment_like_update.php",{comid:comid});
			});
		} else if (data.includes("not_belonged")) {
			openWrap("You do not belong to this course");
		} else if (data.includes("is_admin")) {
			openWrap("Admin cannot like comment");
		}
	});
}
function commentEdit(comid,pid) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_post.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		var body = $("textarea#textarea_body_comment_edit_comid_"+comid).val();
		if (body != "") {
			$("#save_comment_edit_comid_"+comid).load("/triggers/comment_edit.php",{comid:comid,body:body},function(){
				$("#comments_pid_"+pid).load("/triggers/comments_update.php",{pid:pid});
			});
		} else if (data.includes("not_belonged")) {
			openWrap("You do not belong to this course");
		} else if (data.includes("is_admin")) {
			openWrap("Admin cannot like comment");
		}
	});
}
function commentDelete(comid,pid) {
	$("#save_comment_delete_comid_"+comid).load("/triggers/comment_delete.php",{comid:comid},function(){
		$("#comments_pid_"+pid).load("/triggers/comments_update.php",{pid:pid});
		$("#comments_count_pid_"+pid).load("/triggers/comments_count_update.php",{pid:pid});
		closeWrapBox();
	});
}
function commentDeleteDialog(comid,pid) {
	$("#wrap-content").load("/triggers/comment_delete_dialog.php",{comid:comid,pid:pid});
	openEmptyWrap();
}
function commentCreate(pid) {
	$.ajax({
		type: "POST",
		url: "/triggers/validate_post.php",
		data: {
			"pid": pid
		},
		dataType: "text"
	}).done(function(data){
		if (!data.includes("not_belonged") && !data.includes("is_admin") || (data.includes("guest_mode") && !data.includes("is_admin"))) {
			var body = $("textarea#textarea_body_comment_create_pid_"+pid).val();
			var hide = $("input#input_hide_comment_create_pid_"+pid+":checked").val();
			var uid = $("input#input_uid_comment_create_pid_"+pid).val();
			if (body != "") {
				$("#save_comment_create_pid_"+pid).load("/triggers/comment_create.php",{pid:pid,uid:uid,body:body,hide:hide},function(){
					$("#comments_pid_"+pid).load("/triggers/comments_update.php",{pid:pid});
					$("#comments_count_pid_"+pid).load("/triggers/comments_count_update.php",{pid:pid});
				});
			} else {
				openWrap("Please fill in the comment body");
			}
		} else if (data.includes("not_belonged")) {
			openWrap("You do not belong to this course");
		} else if (data.includes("is_admin")) {
			openWrap("Admin cannot comment on post");
		}
	});
}