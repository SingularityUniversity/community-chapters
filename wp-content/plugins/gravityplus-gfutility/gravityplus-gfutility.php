<?php

/**
 * @wordpress-plugin
 * Plugin Name: Gravity Forms Utility
 * Plugin URI: https://gravityplus.pro/gravity-forms-utility
 * Description: A collection of tools to make your life easier when working with Gravity Forms. Have an idea for a new tool? Email support@gravityplus.pro.
 * Version: 1.2.0
 * Author: gravity+
 * Author URI: https://gravityplus.pro
 * Text Domain: gfp-utility
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package   GFP_Utility
 * @version   1.2.0
 * @author    gravity+ <support@gravityplus.pro>
 * @license   GPL-2.0+
 * @link      https://gravityplus.pro
 * @copyright 2014 gravity+
 *
 * last updated: October 1, 2014

 */
class GFP_GF_Utility {

	/**
	 * Instance of this class.
	 *
	 * @since    1.2.0
	 *
	 * @var      object
	 */
	private static $_this = null;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.2.0
	 *
	 * @var     string
	 */
	protected $version = '1.2.0';

	public function __construct () {

		self::$_this = $this;
	}

	public function run () {
		add_action( 'plugins_loaded', array( $this, 'load_tools' ) );
	}

	public function load_tools () {
		if ( class_exists( 'GFForms' ) ) {
			$this->load_textdomain();

			require_once( GFP_GF_UTILITY_PATH . '/tools/show-page-on-form-list.php' );
			require_once( GFP_GF_UTILITY_PATH . '/tools/prevent-entry-creation.php' );
			require_once( GFP_GF_UTILITY_PATH . '/tools/toggle-all-fields-required.php' );
			require_once( GFP_GF_UTILITY_PATH . '/tools/redact.php' );
		}
	}

	public function load_textdomain () {

		$gfp_gf_utility_lang_dir = dirname( plugin_basename( GFP_GF_UTILITY_FILE ) ) . '/languages/';
		$gfp_gf_utility_lang_dir = apply_filters( 'gfp_gf_utility_language_dir', $gfp_gf_utility_lang_dir );

		$locale = apply_filters( 'plugin_locale', get_locale(), 'gfp-utility' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'gfp-utility', $locale );

		$mofile_local  = $gfp_gf_utility_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/gfp-utility/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			load_textdomain( 'gfp-utility', $mofile_global );
		}
		elseif ( file_exists( $mofile_local ) ) {
			load_textdomain( 'gfp-utility', $mofile_local );
		}
		else {
			load_plugin_textdomain( 'gfp-utility', false, $gfp_gf_utility_lang_dir );
		}
	}

	public static function get_version () {
		return self::$_this->version;
	}
}

define( 'GFP_GF_UTILITY_FILE', __FILE__ );
define( 'GFP_GF_UTILITY_PATH', plugin_dir_path( __FILE__ ) );
define( 'GFP_GF_UTILITY_URL', plugin_dir_url( __FILE__ ) );

$gfutility = new GFP_GF_Utility();
$gfutility->run();