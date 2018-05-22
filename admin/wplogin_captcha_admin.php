<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists('wplogin_captcha_admin') )
{

	class wplogin_captcha_admin {
	
		public $error_type;

		public $error_message;

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'wplogin_custom_adminbar' ) );

			if(get_option( 'fresh_install' )):
				add_action( 'admin_notices', array( $this, 'sweetguide' ) );
			endif;	

			if(isset($_POST['saving'])):
				$this->saving_info();
			endif;
		}

		public function sweetguide()
		{
			$plugin_home = admin_url()."admin.php?page=wp-login-captcha-fields";
			?>
			<div class="notice notice-success is-dismissible">			
			    <p>Thank you for installing the plugin, click <a href="<?php echo $plugin_home; ?>">wp-login Captcha</a> to configure the simple settings :)</p>
			</div>
			<?php
		}

		public function wplogin_custom_adminbar() {

			add_menu_page( 
				'wp-login Captcha', 
				'wp-login Captcha', 
				'edit_dashboard', 
				'wp-login-captcha-fields', 
				array($this,'fields'), 
				'dashicons-sos',
				999
			);
		}

		public function saving_info()
		{

			$this->error_type = "notice-error";
			if(!$_POST['site_key'] || !$_POST['secret_key']):
				$this->error_message = "All Fields are Required";
			elseif(!isset($_POST['type'])):
				$this->error_message = "Please select CAPTCHA type";
			else:
				update_option('wplogin_google_key',$_POST['site_key']);
				update_option('wplogin_google_s_key',$_POST['secret_key']);
				update_option('wplogin_google_type',$_POST['type']);
				$this->error_type = "notice-success";
				$this->error_message = "Done saving :)";
			endif;

			add_action( 'admin_notices', array( $this, '_error_msg' ) );
		}

		public function _error_msg()
		{		
			?>
			<div class="notice is-dismissible <?php echo $this->error_type; ?>">
				<p><?php echo $this->error_message; ?></p>
			</div>
			<?php
		}

		public function fields()
		{
			wp_enqueue_script( 'google__recaptcha_nocallback', 'https://www.google.com/recaptcha/api.js', array(), false, true );
			wp_enqueue_script( 'wplogin__main', plugins_url()."/wplogin_captcha/js/main.js", array(), false, true );
			update_option( 'fresh_install', 0 );
			?>
			<div id="wpbody-content">
				<div class="wrap">
					<h1>WP-Login Captcha Settings</h1>
					<form action="admin.php?page=wp-login-captcha-fields" method="post">
						<table class="form-table">
							<tbody>
								<tr>
									<th colspan="2"><p class="description" id="tagline-description">Get your api key here <a href='https://www.google.com/recaptcha/' target="_blank">www.google.com/recaptcha</a></p></th>
								</tr>
								<tr>
									<th scope="row">
										<label for="">Google API Site key</label>
									</th>
									<td>
										<input type="text" name="site_key" value="<?php echo get_option('wplogin_google_key') ? get_option('wplogin_google_key') : ""; ?>" class="regular-text">
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="">Google API Secret key</label>
									</th>
									<td>
										<input type="text" name="secret_key" value="<?php echo get_option('wplogin_google_s_key') ? get_option('wplogin_google_s_key') : ""; ?>" class="regular-text">
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="">Type of captcha</label>
									</th>
									<td>
										<label for="normal"><input id="normal" type="radio" name="type" value="normal" <?php echo (get_option('wplogin_google_type')=="normal") ? "CHECKED" : ""; ?>> NORMAL</label>
										<label for="invi"><input id="invi" type="radio" name="type" value="invi" <?php echo (get_option('wplogin_google_type')=="invi") ? "CHECKED" : ""; ?>> INVINSIBLE</label>
									</td>
								</tr>
								<?php if(get_option('wplogin_google_key') and get_option('wplogin_google_s_key')): ?>								
								<tr>
									<th scope="row">
										<label for="">Captcha Preview</label>
									</th>
									<td>										
										<?php if(get_option('wplogin_google_type')=="normal"): ?>

											<div class="g-recaptcha" data-sitekey="<?php echo get_option('wplogin_google_key'); ?>"></div>
										
										<?php else: ?>

											<button class="g-recaptcha" style="display: none;" data-badge="inline" data-sitekey="<?php echo get_option('wplogin_google_key'); ?>"></button>

										<?php endif; ?>
									</td>
								</tr>
								<?php endif; ?>																
							</tbody>
						</table>
						<p><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
						<input type="hidden" name="saving" value="true">
						
					</form>
				</div>
			</div>		
			<?php
		}
	}
	new wplogin_captcha_admin();
}