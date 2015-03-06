<?php

/**
 * Fired during plugin activation
 *
 * @link       http://mediacause.org
 * @since      1.0.0
 *
 * @package    Classy
 * @subpackage Classy/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Classy
 * @subpackage Classy/includes
 * @author     Media Cause <web@mediacause.org>
 */
class Classy_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option('classy_token', '', '', 'yes');
		add_option('classy_cid', '', '', 'yes');
	}

}
