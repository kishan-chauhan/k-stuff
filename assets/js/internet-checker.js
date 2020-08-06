jQuery(document).ready(function($){
	//remove notification div
	setTimeout(function(){
		$('.success').fadeOut();
	}, 3000);
	setTimeout(function(){
		$('.error').fadeOut();
	}, 3000);
});