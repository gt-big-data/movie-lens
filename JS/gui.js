function reAlign() {
var halfHeight = ($(window).height()-10)/2;
$('#signInDiv').css({'top': ((2*halfHeight-$('#signInDiv').height())/3)+'px', 'left': (($(window).width()-$('#signInDiv').width())/2)+'px'});
}
function choose(which, movieId) {
	document.title=' Movie: '+movieId+' and which: '+which;
$('.starsOpac').removeClass('starsOpac');
for(var i = 1; i <= (which+1); i++) {
	$('#m'+movieId+' .star'+i).attr('class', 'stars starsOpac star'+i);
}
}
function login() {
	$.ajax({url: 'ajax/login.php?user='+escape($('#user').val())+'&pass='+escape($('#pass').val())+'&mathrand='+(Math.floor(Math.random() * 51000)), success: function(dat){
		dati = dat.split('^!bd!^'); var id = parseInt(dati[0]);
		if(dati.length != 2 || id == 0 || isNaN(dati[0])) {alert('User not found ('+dat+')');}
		else {
		$.cookie('dbUser', id);
		$.cookie('dbPass', dati[1]);
		location.reload();
		}
	}
	});
}
function addRating(howMany, movieId) {
	$('#m'+movieId).fadeOut(200);
	$.ajax({url: 'ajax/rating.php?user='+escape($.cookie('dbUser'))+'&pass='+escape($.cookie('dbPass'))+'&rating='+howMany+'&movie='+movieId+'&mathrand='+(Math.floor(Math.random() * 51000)), success: function(dat){
		$('.movieClass').remove();
		$('body').append(dat);
	}
	});
}
reAlign();