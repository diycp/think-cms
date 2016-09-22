$(function() {
	$('.login-code-change').on('click', function() {
		var src = $('.login-code').attr('data-src');
		$('.login-code').attr('src', src + '?_=' + Math.random());
		$('#verify_code').val('');
		$('#verify_code').focus();
	});
	
	$('#user_passwd').keydown(function(e) {
		if (e.keyCode == 13) {
			$('.ajax-post').click();
		}
	});
	
	$('#verify_code').keydown(function(e) {
		if (e.keyCode == 13) {
			$('.ajax-post').click();
		}
	});
});