<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://mediacause.org
 * @since      1.2
 *
 * @package    Classy
 * @subpackage Classy/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @package    Classy
 * @subpackage Classy/admin
 * @author     Media Cause <web@mediacause.org>
 */
class Classy_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.2
	 * @access   private
	 * @var      string    $classy    The ID of this plugin.
	 */
	private $classy;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.2
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.2
	 * @param      string    $classy       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $classy, $version ) {
		$this->classy = $classy;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.2
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->classy, plugin_dir_url( __FILE__ ) . 'css/classy-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.2
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->classy, plugin_dir_url( __FILE__ ) . 'js/classy-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function admin_menus(){
		if(isset($_POST['update'])){
			$account = new Classy_Account($_POST['token'], $_POST['cid']);
			$account->update() ? add_action( 'admin_notices', 'valid_api_notice' ) : add_action( 'admin_notices', 'invalid_api_notice');
		}	

		add_menu_page( 'Classy', 'Classy', 'manage_options', plugin_dir_path( __FILE__ ) . 'partials/classy-admin-display.php', '', plugin_dir_url(__FILE__) . 'img/classy.png'); 

		function invalid_api_notice() {
		    echo '<div class="error">
		        	<p>Invalid Token or CID</p>
		    	</div>';
		}

		function valid_api_notice() {
		    echo '<div class="updated">
		        	<p>Successfully added Token & CID</p>
		    	</div>';
		}
	}
}
