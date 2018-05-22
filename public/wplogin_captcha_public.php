<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists('wplogin_captcha_public') )
{
	class wplogin_captcha_public {
	
		public function __construct() {
						
			add_filter( 'authenticate', array($this,'googlecaptcha_validation'), 22 );
			if(get_option('wplogin_google_type')=="normal"):							
				add_action( 'login_form', array($this,'google_captcha_v2'), 99);
			else:
				add_action( 'login_enqueue_scripts', array($this,'wplogin_external_invi') );
			endif;
		}

		function wplogin_external_invi() {
			
			wp_enqueue_script( 'google__recaptcha', 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit', array(), false, true );
			wp_enqueue_script( 'wplogin__main', plugins_url()."/wplogin_captcha/js/main.js", array(), false, true );
			wp_localize_script( 'wplogin__main', 'o_0', array(
				'site_key'	=>	get_option('wplogin_google_key'),
				'secret_key'=>	get_option('wplogin_google_s_key')
			) );
		}

		public function google_captcha_v2()
		{
			wp_enqueue_script( 'google__recaptcha_nocallback', 'https://www.google.com/recaptcha/api.js', array(), false, true );
			$key = get_option('wplogin_google_key');
			echo "<div class='g-recaptcha' data-sitekey='$key' style='transform:scale(0.90);-webkit-transform:scale(0.90);transform-origin:0 0;-webkit-transform-origin:0 0;margin-bottom:6px;'></div>";
		}

		function googlecaptcha_validation($user){
			
			if(	isset($_POST["g-recaptcha-response"]) && empty($_POST["g-recaptcha-response"]) ):
				$error = new WP_Error();
				$error->add('invalid_captcha', __('<strong>ERROR</strong>: Invalid captcha.'));
				return $error;
			endif;
			return $user;
		}
	}
	new wplogin_captcha_public();
}