function toggle(id,link) {
	var e = document.getElementById(id);
	if (e.style.display == 'block') {
		e.style.display = 'none';
		link.innerHTML = 'Debug off';
	} else {
		e.style.display = 'block';
		link.innerHTML = 'Debug on';
	}
}
function toggle_comments(id) {
	var e = document.getElementById(id);
	if (e.style.display == 'block') {
		e.style.display = 'none';
	} else {
		e.style.display = 'block';
	}
}
function toggle_comment_edit(id,link) {
	var e = document.getElementById(id);
	if (e.style.display == 'block') {
		e.style.display = 'none';
		link.innerHTML = 'Edit';
	} else {
		e.style.display = 'block';
		link.innerHTML = 'Cancel edit';
	}
}
function show_comments(id) {
	var e = document.getElementById(id);
	if (e.style.display == 'none') {
		e.style.display = 'block';
	}
}
function openLogin() {
	$("#overlay").css("width",$(document).width()).css("height",$(document).height()).css('display','block').click(function(){$('#overlay,#login-wrap').fadeOut(500);});
	$("#login-wrap").css('display','block');
	$("#login input[type='text'],#login input[type='password']").val('');
}
function closeLoginBox() {
	var overlay = $("#overlay");
	var box = $("#login-wrap");
	overlay.fadeOut(500);
	box.fadeOut(500);
}
function openWrap(str) {
	$("#overlay").css("width",$(document).width()).css("height",$(document).height()).css('display','block').click(function(){
		$('#overlay,#wrap').fadeOut(500);
		$("#wrap-content").html("");
	});
	$("#wrap-content").html(str);
	$("#wrap").css('display','block');
	$('#wrap-border').css('height','223px');
}
function openEmptyWrap() {
	$("#overlay").css("width",$(document).width()).css("height",$(document).height()).css('display','block').click(function(){
		$('#overlay,#wrap').fadeOut(500);
		$("#wrap-content").html("");
	});
	$("#wrap").css('display','block');
	$('#wrap-border').css('height','223px');
}
function closeWrapBox() {
	var overlay = $("#overlay");
	var box = $("#wrap");
	$("#wrap-content").html("");
	overlay.fadeOut(500);
	box.fadeOut(500);
}
function clickclear(thisfield, defaulttext) {
if (thisfield.value == defaulttext) {
thisfield.value = "";
}
}
 
function clickrecall(thisfield, defaulttext) {
if (thisfield.value == "") {
thisfield.value = defaulttext;
}
}
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
function limits(obj, limit){
	var text = $(obj).val(); 
	var length = text.length;
	if(length > limit){
		$(obj).val(text.substr(0,limit));
		openWrap("Character limit is "+limit);
	}
}