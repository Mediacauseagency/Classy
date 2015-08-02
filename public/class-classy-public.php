<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://mediacause.org
 * @since      1.2.2
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
	 * @since      1.2.2
	 * @access   private
	 * @var      string    $classy    The ID of this plugin.
	 */
	private $classy;

	/**
	 * The version of this plugin.
	 *
	 * @since      1.2.2
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The version of this plugin.
	 *
	 * @since      1.2.2
	 * @access   private
	 * @var      object    $api    The current account API Object
	 */
	private $api;

	private $token;
	private $cid;
	public $classy_url;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since      1.2.2
	 * @param      string    $classy       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $classy, $version ) {
		$this->classy = $classy;
		$this->version = $version;
		$this->api = new Classy_API();
		$this->token = get_option('classy_token');
		$this->cid = get_option('classy_cid');
		$this->classy_url = get_option('classy_url');
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since      1.2.2
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->classy, plugin_dir_url( __FILE__ ) . 'css/classy-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since      1.2.2
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->classy, plugin_dir_url( __FILE__ ) . 'js/classy-public.js', array( 'jquery' ), $this->version, false );
	}

	public function register_shortcodes() {
		add_shortcode('classy_campaigns', array($this, 'classy_get_campaigns_func'));
		add_shortcode('classy_fundraisers', array($this, 'classy_get_fundraisers_func'));
		add_shortcode('classy_donations', array($this, 'classy_get_donations_func'));
		add_shortcode('classy_campaign_info', array($this, 'classy_get_campaign_info_func'));
		add_shortcode('classy_fundraiser_info', array($this, 'classy_get_fundraiser_info_func'));
		add_shortcode('classy_teams', array($this, 'classy_get_teams_func'));
		add_shortcode('classy_team_info', array($this, 'classy_get_team_info_func'));
		add_shortcode('classy_recurring', array($this, 'classy_get_recurring_func'));
		add_shortcode('classy_project_info', array($this, 'classy_get_project_info_func'));
	}

	// Gets Latest campaigns created
	function classy_get_campaigns_func($atts){
		// Shortcode Attributes Setup
		$a = shortcode_atts( array(
			'eid' => '',
			'q' => '',
			'zip' => '',
			'within' => '',
			'limit' => '3',
		), $atts );

		// Build URL Parameters
		$attrs = http_build_query($a);
		$result = $this->api->campaigns($attrs);

		$count = 0; 
		if($result->status_code == "SUCCESS"){
			$campaigns = $result->campaigns;

			$output = '<div class="classy campaigns-container">
								<div class="campaigns">';
						foreach ($campaigns as $campaign){
						    $date = strtotime($campaign->start_date);
						    $location = trim($campaign->address) == false ? $campaign->venue : $campaign->address;
							$output .= '
									<div class="single-campaign">
										<p class="campaign-title"><a href="'. $campaign->event_url .'">'. $campaign->name .'</a></p>
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

	// Gets specific Campaign info
	function classy_get_campaign_info_func($atts){
		$a = shortcode_atts( array(
			'eid' => '',
			'tickets' => 'false'
		), $atts );

		// Get Event ID
		$eid = $a['eid'];
		$result = $this->api->campaign_info($eid);

		if($result->status_code == "SUCCESS"){
			$campaign = $result;
		    $date = strtotime($campaign->start_date);
		    $location = trim($campaign->address) == false ? $campaign->venue : $campaign->address;
		    $image = empty($campaign->event_image_large) ? plugin_dir_url( __FILE__ ) . 'img/classy.png' : $campaign->event_image_large;

			$output = '<div class="classy campaign-container">
								<div class="campaign">';
			$output .= '<div class="single-campaign">
							<div class="single-campaign-thumbnail">
								<img src="'. $image .'">
							</div>
							<div class="single-campaign-info">
								<p class="campaign-title"><a href="'. $campaign->event_url .'">'. $campaign->name .'</a></p>
								<p class="campaign-date">'. esc_attr(date_i18n('d M, Y',$date)) .'</p>
								<p class="campaign-address">'. $location . ', ' . $campaign->city . ', ' . $campaign->state .'</p>';
			$output .=		'</div>
						</div>';
			$output .= '</div></div>';

			// var_dump($result);
			return $output;							
		}
	}

	// Get latest fundraisers
	function classy_get_fundraisers_func($atts){
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

		// Build URL Parameters
		$attrs = http_build_query($a);
		$result = $this->api->fundraisers($attrs);

		if($result->status_code == "SUCCESS"){
			$fundraisers = $result->fundraisers;
			$output = '<div class="classy fundraisers-container">
								<div class="fundraisers">';
						foreach ($fundraisers as $fundraiser){
							$image = empty($fundraiser->member_image_medium) ? plugin_dir_url( __FILE__ ) . 'img/single.png' : $fundraiser->member_image_medium;
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

	// Get Fundraiser Information
	function classy_get_fundraiser_info_func($atts){
		$a = shortcode_atts( array(
			'fcid' => '',
		), $atts );

		// Get Event ID
		$fcid = $a['fcid'];
		$result = $this->api->fundraiser_info($fcid);

		if($result->status_code == "SUCCESS"){
			$fundraiser = $result;
			$image = empty($fundraiser->member_image_medium) ? plugin_dir_url( __FILE__ ) . 'img/single.png' : $fundraiser->member_image_medium;

			$output = '<div class="single-fundraiser">
						<div class="fundraiser-image-container">
							<img src="'. $image .'">
						</div>
						<div class="fundraiser-details">
							<p class="fundraiser-name"><a href="'. $fundraiser->donation_url .'">'. $fundraiser->member_name .'</a></p>
							<p class="fundraiser-event"><a href="'. $fundraiser->fundraiser_url .'">'. $fundraiser->event_name .'</a></p>
							<p class="fundraiser-goal">Fundraiser Goal: $'. $fundraiser->total_raised .'/'. $fundraiser->goal .'</p>
						</div>
					</div>';
			$output .= '</div></div>';

			return $output;
		}
	}

	// Get All Teams
	function classy_get_teams_func($atts){
		$a = shortcode_atts(array(
				'eid' => '',
				'ftid' => '',
				'mid' => '',
				'limit' => '',
				'order' => ''
			), $atts);

		// Build URL Parameters
		$attrs = http_build_query($a);
		$result = $this->api->teams($attrs);

		if($result->status_code == "SUCCESS"){
			$teams = $result->teams;
			$output = '<div class="classy teams-container">
								<div class="teams">';
						foreach ($teams as $team){
							$image = empty($team->team_image_medium) ? plugin_dir_url( __FILE__ ) . 'img/group.png' : $team->team_image_medium;
							$output .= '<div class="single-team">
											<div class="image-container">
												<img src="'. $image .'">
											</div>
											<div class="team-details">
												<p class="team-name"><a href="'. $team->team_url .'">'. $team->team_name . '</a></p>
												<p class="team-event"><a href="'. $team->donation_url .'">'. $team->charity_name .'</a></p>
											</div>
										</div>';
						}
			$output .= '</div></div>';

			return $output;
		}

	}

	// Get Team Information
	function classy_get_team_info_func($atts){
		$a = shortcode_atts( array(
			'ftid' => '',
		), $atts );

		// Get Event ID
		$ftid = $a['ftid'];
		$result = $this->api->team_info($ftid);

		if($result->status_code == "SUCCESS"){
			$team = $result;
			$image = empty($team->team_image_medium) ? plugin_dir_url( __FILE__ ) . 'img/group.png' : $team->team_image_medium;

			$output = '<div class="single-team">
						<div class="team-image-container">
							<img src="'. $image .'">
						</div>
						<div class="team-details">
							<p class="team-name"><a href="'. $team->team_url .'">'. $team->team_name .'</a></p>
							<p class="team-event"><a href="'. $team->donation_url .'">'. $team->charity_name .'</a></p>
							<p class="team-goal">Team Goal: $'. $team->total_raised .'/'. $team->goal .'</p>
						</div>
					</div>';
			$output .= '</div></div>';

			return $output;
		}
	}


	// Get Latest Donations
	function classy_get_donations_func($atts){
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

		// Build URL Parameters
		$attrs = http_build_query($a);
		$result = $this->api->donations($attrs);

		if($result->status_code == "SUCCESS"){
			$donations = $result->donations;
			$output = '<div class="classy donations-container"><div class="donations">';
			foreach ($donations as $donation) {
				if($donation->fundraiser_id != 0){
					$res = $this->api->fundraiser_info($donation->fundraiser_id);
					$f_url = $res->fundraiser_url;
				} else {
					$f_url = 'https://classy.org/' . $this->classy_url;
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

	// Get Recurring Donations
	function classy_get_recurring_func($atts){
		$a = shortcode_atts(array(
				'eid' => '',
				'mid' => '',
				'rid' => '',
				'limit' => ''
			));

		$attrs = http_build_query($a);
		$result = $this->api->recurring($attrs);

		if($result->status_code == "SUCCESS"){
			$donations = $result->profiles;
			$output = '<div class="classy donations-container"><div class="donations">';
			foreach ($donations as $donation) {
				if($donation->event_id != 0){
					$campaign = $this->api->campaign_info($donation->event_id);
					$f_url = $res->fundraiser_url;
				} else {
					$f_url = 'https://classy.org/' . $this->classy_url;
				}
				
				$output .= '<div class="single-donation">
								<p class="donator"><span class="donator-name">'. $donation->member_name .'</span> has donated to <a href="'. $campaign->event_url .'" target="_blank">'. $campaign->name .'</a></p>
							</div>';
			}
			$output .= '</div></div>';

			return $output;
		}		
	}

	// Get Project Information
	function classy_get_project_info_func($atts){
		$a = shortcode_atts(array(
				'pid' => ''
			), $atts);

		$pid = $a['pid'];
		$project = $this->api->project_info($pid);

		if($project->status_code == "SUCCESS"){
			$output = '<div class="classy donations-container"><div class="donations">
						<p class="project-name">Project Name: ' . $project->project_name .'</p>
						<p class="total-raised">Total Raised: ' . $project->total_raised .'</p></div></div>';
			return $output;
		}
	}
}
