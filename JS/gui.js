var movies = [];
function initMovies(jsonRatings, jsonNeighbors) {
rating = JSON.parse(jsonRatings);
neighbors = JSON.parse(jsonNeighbors);
for (var i = neighbors.length - 1; i >= 0; i--) {
	thisMovie = rating[i].m;
	movies[thisMovie] = {};
	movies[thisMovie].r = rating[i].r;
	movies[thisMovie].n = neighbors[i].n.split(',');
}
}
function nextMovie(previousMovie) {
var found = 0;
if(movies[previousMovie].r >= 3) { // continue on this path
found = getNonBusy(previousMovie);
}
if(found == 0) { // haven't found something, try to create a seed
for(var mov in movies) {
	if(found == 0 && movies[mov].r >= 4) {
		found = getNonBusy(mov);

	}
}
}
loadAjax(found);
}
function getNonBusy(which) {
var i = 0; var found = 0;
while(i <= 5 && found == 0) {
	if((movies[movies[which].n[i]])) {i ++;}
	else {
		found =movies[which].n[i]; 
	}
}
return found;
}
function reAlign() {
var halfHeight = ($(window).height()-10)/2;
$('#signInDiv').css({'top': ((2*halfHeight-$('#signInDiv').height())/3)+'px', 'left': (($(window).width()-$('#signInDiv').width())/2)+'px'});
}
function addRating(howMany, movieId) {
	$('#m'+movieId).fadeOut(200);
	$.ajax({url: 'ajax/rating.php?user='+escape($.cookie('dbUser'))+'&pass='+escape($.cookie('dbPass'))+'&rating='+howMany+'&movie='+movieId+'&mathrand='+(Math.floor(Math.random() * 51000)), success: function(dat){
	movies[movieId] = {};
	movies[movieId].r = howMany;
	movies[movieId].n = dat.split(',');
	nextMovie(movieId);
	}
	});
}
function loadAjax(movie) {
	$.ajax({url: 'ajax/loadMovie.php?movie='+movie+'&mathrand='+(Math.floor(Math.random() * 51000)), success: function(data) {
		$('.movieClass').remove();
		$('body').append(data);
	}
	});
}
function choose(which, movieId) {
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
window.onload=function() {
reAlign();
}