var onSubmit = function(token) {
	document.getElementById('loginform').submit();
};
var onloadCallback = function() {
	grecaptcha.render('wp-submit', {
		'sitekey'	: o_0.site_key,
		'callback'	: onSubmit
	});
};