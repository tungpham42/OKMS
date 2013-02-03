$(document).ready(function() {
	$("[id^=rating_]").hover(function() {
		rid = $(this).attr("id").split("_")[1];
		$("#rating_"+rid).children("[class^=star_]").children('img').hover(function() {
			$("#rating_"+rid).children("[class^=star_]").children('img').removeClass("hover");
			/* The hovered item number */
			var hovered = $(this).parent().attr("class").split("_")[1];
			while(hovered > 0) {
				$("#rating_"+rid).children(".star_"+hovered).children('img').addClass("hover");
				hovered--;
			}
		});
	});
	$("[id^=rating_]").children("[class^=star_]").click(function() {
		var current_star = $(this).attr("class").split("_")[1];
		var rid = $(this).parent().attr("id").split("_")[1];
		$('#rating_'+rid).load('triggers/rate.php', {rating: current_star, id: rid});
	});
});