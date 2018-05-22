<?php
/*
Plugin Name: wplogin_captcha
Plugin URI:  https://github.com/robert-john-concepcion
Description: If you (human) want to show CAPTCHA (google invinsible recaptcha or normal captcha) in your wp login form.
Version:     1
Author:      Robert John Concepcion
Author URI:  https://github.com/robert-john-concepcion
Text Domain: wporg
Domain Path: /languages
License:     GPL2
 
Backend Invi Captcha is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Backend Invi Captcha is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Backend Invi Captcha. If not, see https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( !class_exists('wplogin_captcha') )
{

	/**
	 * Initialization
	 *
	 * @class       wplogin_captcha
	 * @version     1
	 * @package     wplogin_captcha
	 * @category    Core
	 * @author      Robert John Concepcion
	 */
	class wplogin_captcha {

		public function __construct()
		{
			$this->includes();				
		}

		public static function install() {
			update_option( 'fresh_install', 1 );		
		}

		private function includes(){
			if(get_option('wplogin_google_key') and get_option('wplogin_google_s_key'))
				include_once 'public/wplogin_captcha_public.php';
			include_once 'admin/wplogin_captcha_admin.php';					
		}
	}
	new wplogin_captcha();
	register_activation_hook( __FILE__, array( 'wplogin_captcha', 'install' ) );
}