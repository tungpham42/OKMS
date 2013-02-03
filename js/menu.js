$(document).ready(function() {
    var inprog = 0;
	$("#navigation ul.instruments").hover(function() {
	if (inprog == 0) {
             	inprog = 1;
     		$("#navigation ul.instruments").children("#navigation ul.instruments li").slideDown(400);
     		}
	}, function() {
     		$("#navigation ul.instruments").children("#navigation ul.instruments li").slideUp(200, function() {inprog = 0;});
	});
	});
    
    $(document).ready(function() {
    var inprog = 0;
	$("#navigation ul.accessories").hover(function() {
	if (inprog == 0) {
             	inprog = 1;
     		$("#navigation ul.accessories").children("#navigation ul.accessories li").slideDown(400);
     		}
	}, function() {
     		$("#navigation ul.accessories").children("#navigation ul.accessories li").slideUp(200, function() {inprog = 0;});
	});
	});