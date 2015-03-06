<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://mediacause.org
 * @since      1.0.0
 *
 * @package    Classy
 * @subpackage Classy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Classy
 * @subpackage Classy/public
 * @author     Media Cause <web@mediacause.org>
 */
class Classy_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $classy    The ID of this plugin.
	 */
	private $classy;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $classy       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $classy, $version ) {

		$this->classy = $classy;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->classy, plugin_dir_url( __FILE__ ) . 'css/classy-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->classy, plugin_dir_url( __FILE__ ) . 'js/classy-public.js', array( 'jquery' ), $this->version, false );
	}

	public function register_shortcodes() {
		add_shortcode('classy_campaigns', array($this, 'get_campaigns_func'));
		add_shortcode('classy_fundraisers', array($this, 'get_fundraisers_func'));
		add_shortcode('classy_donations', array($this, 'get_donations_func'));
	}

	// Gets Latest campaigns Created
	function get_campaigns_func($atts){
		// Shortcode Attributes Setup
		$a = shortcode_atts( array(
			'eid' => '',
			'q' => '',
			'zip' => '',
			'within' => '',
			'limit' => '3',
		), $atts );

		// Get Token and CID
		$a['token'] = get_option('classy_token');
		$a['cid'] = get_option('classy_cid');

		// Build URL Parameters
		$attrs = http_build_query($a);
		$url = 'https://www.classy.org/api1/campaigns?' . $attrs;

		// Curl it
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		$output = json_decode($output);
		curl_close($ch);

		$count = 0; 
		if($output->status_code == "SUCCESS"){
			$campaigns = $output->campaigns;

			$output = '<div class="classy campaigns-container">
								<div class="campaigns">';
						foreach ($campaigns as $campaign){
						    $date = strtotime($campaign->start_date);
						    if($campaign->address){
						    	$location = $campaign->venue;
						    } else {
						    	$campaign->address;
						    }
							$output .= '
									<div class="single-campaign">
										<p class="campaign-title"><a href="'. $campaign->campaign_url .'">'. $campaign->name .'</a></p>
										<p class="campaign-date">'. esc_attr(date_i18n('d M, Y',$date)) .'</p>
										<p class="campaign-address">'. $location . ', ' . $campaign->city . ', ' . $campaign->state .'</p>
									</div>';

							// Temporary fix for limit until Classy fixes limit on API
							$count++;
							if($count == $a['limit'])
								break;
						}
			$output .= '</div></div>';
		}

		$output .= '</ul></div>';

		return $output;
	}

	function get_fundraisers_func($atts){
		// Shortcode Attributes Setup
		$a = shortcode_atts( array(
			'eid' => '',
			'fcid' => '',
			'pid' => '',
			'fb_uid' => '',
			'mid' => '',
			'email' => '',
			'q' => '',
			'limit' => '3',
			'featured' => '',
			'cre_date_start' => '',
			'cre_date_end' => '',
			'goal_reached' => '',
			'goal_not_reached' => '',
			'near_goal' => '',
			'needs_help' => '',
			'order' => 'most_recent'
		), $atts );

		// Get Token and CID
		$a['token'] = get_option('classy_token');
		$a['cid'] = get_option('classy_cid');

		// Build URL Parameters
		$attrs = http_build_query($a);
		$url = 'https://www.classy.org/api1/fundraisers?' . $attrs;

		// Curl it
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		$output = json_decode($output);
		curl_close($ch);

		if($output->status_code == "SUCCESS"){
			$fundraisers = $output->fundraisers;
			$output = '<div class="classy fundraisers-container">
								<div class="fundraisers">';
						foreach ($fundraisers as $fundraiser){
							$image = empty($fundraiser->member_image_medium) ? plugin_dir_url( __FILE__ ) . 'img/silhouette.jpg' : $fundraiser->member_image_medium;
							$output .= '<div class="single-fundraiser">
											<div class="image-container">
												<img src="'. $image .'">
											</div>
											<div class="fundraiser-details">
												<p class="fundraiser-name"><a href="'. $fundraiser->donation_url .'">'. $fundraiser->first_name . ' ' . $fundraiser->last_name .'</a></p>
												<p class="fundraiser-event"><a href="'. $fundraiser->fundraiser_url .'">'. $fundraiser->event_name .'</a></p>
											</div>
										</div>';

						}
			$output .= '</div></div>';

			return $output;
		}
	}

	function get_donations_func($atts){
		// Shortcode Attributes Setup
		$a = shortcode_atts( array(
			'eid' => '',
			'fcid' => '',
			'ftid' => '',
			'oid' => '',
			'mid' => '',
			'start_date' => '',
			'end_date' => '',
			'show_anonymous' => '1',
			'limit' => '3'
		), $atts );

		// Get Token and CID
		$a['token'] = get_option('classy_token');
		$a['cid'] = get_option('classy_cid');

		// Build URL Parameters
		$attrs = http_build_query($a);
		$url = 'https://www.classy.org/api1/donations?' . $attrs;

		// Curl it
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		$output = json_decode($output);
		curl_close($ch);

		if($output->status_code == "SUCCESS"){
			$donations = $output->donations;
			$output = '<div class="classy donations-container"><div class="donations">';
			foreach ($donations as $donation) {
				if($donation->fundraiser_id != 0){
					$url = 'https://www.classy.org/api1/fundraiser-info?token=' . $a['token'] . '&cid=' . $a['cid'] .'&fcid='. $donation->fundraiser_id;
					$ch = curl_init(); 
					curl_setopt($ch, CURLOPT_URL, $url); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					$res = curl_exec($ch); 
					$res = json_decode($res);
					curl_close($ch);

					$f_url = $res->fundraiser_url;
				} else {
					$f_url = 'https://classy.org/' . get_option('classy_url');
				}
				
				$full_name = $donation->first_name != "Anonymous" ? $donation->first_name . ' ' . $donation->last_name : 'Anonymous';
				$fundraiser_name = $donation->fundraiser !== null ? $donation->fundraiser : $donation->designation_name;
				$output .= '<div class="single-donation">
								<p class="donator"><span class="donator-name">'. $full_name .'</span> has donated to <a href="'. $f_url .'" target="_blank">'. $fundraiser_name .'</a></p>
							</div>';
			}
			$output .= '</div></div>';

			return $output;
		}

	}
}
