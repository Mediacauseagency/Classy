<?php

/**
 * Sets up Classy API for the various calls
 *
 * @link       http://mediacause.org
 * @since      1.2.2
 *
 * @package    Classy
 * @subpackage Classy/includes
 */

/**
 * Sets up Classy API for the various calls.
 *
 * This class defines all code necessary to run during the plugin's API functionality.
 *
 * @since      1.2.2
 * @package    Classy
 * @subpackage Classy/includes
 * @author     Media Cause <web@mediacause.org>
 */
class Classy_API {
	private $token;
	private $cid;

	public function __construct( $token = null, $cid = null ){
		$this->token = $token ? $token : get_option('classy_token');
		$this->cid = $cid ? $cid : get_option('classy_cid');
	}

	/**
	 * Updates Classy Token and CID information.
	 *
	 * @since      1.2.2
	 */

	public function update(){
		$url = 'https://www.classy.org/api1/account-info?token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		$output = json_decode($output);
		if($output->status_code == "SUCCESS"){
			update_option('classy_token', $this->token);
			update_option('classy_cid', $this->cid);	
			update_option('classy_org_name', $output->name);
			update_option('classy_url', $output->charity_url);
			return true;
		} else {
			update_option('classy_token', $this->token);
			update_option('classy_cid', $this->cid);
			update_option('classy_org_name', '');					
			update_option('classy_url', '');
			return null;
		}	
	}

	/**
	 * Get all the details about a specific charity account
	 *
	 * @since      1.2.2
	 */	
	public function account_info(){
		$url = 'https://www.classy.org/api1/account-info?token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		$output = json_decode($output);
		return $output;	
	}

	/**
	 * Get all site activity for a specific account, campaign, designation, individual 
	 * fundraising page, or fundraising team. Results returned based on most recent 
	 * activity.
	 *
	 * @since      1.2.2
	 * @param 	 string 	$attrs 	The query string (optional)
	 */	
	public function account_activity($attrs = 'limit=10'){
		$url = 'https://www.classy.org/api1/account-activity?' . $attrs . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get information about matching sponsors for a campaign/event 
	 *
	 * @since      1.2.2
	 * @param 	 string 	$attrs 	The query string (optional)
	 */	
	public function account_sponsor_matching($attrs = '') {
		$url = 'https://www.classy.org/api1/account-sponsor-matching?' . $attrs . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get an array of all campaigns and events for a charity account 
	 *
	 * @since      1.2.2
	 * @param 	 string 	$attrs 	The query string (optional)
	 */	
	public function campaigns($attrs = '') {
		$url = 'https://www.classy.org/api1/campaigns?' . $attrs . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get all the specific details about a specific campaign or event 
	 *
	 * @since      1.2.2
	 * @param 	 string 	$eid 	The unique ID of the campaign/event
	 */	
	public function campaign_info($eid) {
		$url = 'https://www.classy.org/api1/campaign-info?eid=' . $eid . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get an array of all tickets for a specified campaign/event 
	 *
	 * @since      1.2.2
	 * @param 	 string 	$eid 	The unique ID of the campaign/event
	 */	
	public function campaign_tickets($eid) {
		$url = 'https://www.classy.org/api1/campaign-tickets?eid=' . $eid . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get array of individual fundraising pages for a specific charity, campaign/event, 
	 * designation or member 
	 *
	 * @since      1.2.2
	 * @param 	 string 	$attrs 	The query string (optional)
	 */	
	public function fundraisers($attrs = '') {
		$url = 'https://www.classy.org/api1/fundraisers?' . $attrs . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get the details for a specific individual fundraising page
	 *
	 * @since      1.2.2
	 * @param 	 string 	$fcid 	The unique ID of the individual fundraising page
	 */	
	public function fundraiser_info($fcid) {
		$url = 'https://www.classy.org/api1/fundraiser-info?fcid=' . $fcid . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get a list of the top fundraising teams ranked by total $ raised for a specific campaign 
	 *
	 * @since      1.2.2
	 * @param 	 string 	$attrs 	The query string (optional)
	 */	
	public function teams($attrs = '') {
		$url = 'https://www.classy.org/api1/teams?' . $attrs . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get the details for a specific individual fundraising team page
	 *
	 * @since      1.2.2
	 * @param 	 string 	$ftid 	The unique ID of the individual fundraising page
	 */	
	public function team_info($ftid) {
		$url = 'https://www.classy.org/api1/team-info?ftid=' . $ftid . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get an array of donations over a specific date range 
	 *
	 * @since      1.2.2
	 * @param 	 string 	$attrs 	The query string (optional)
	 */	
	public function donations($attrs = '') {
		$url = 'https://www.classy.org/api1/donations?' . $attrs . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get an array of recurring donation profiles 
	 *
	 * @since      1.2.2
	 * @param 	 string 	$attrs 	The query string (optional)
	 */	
	public function recurring($attrs = '') {
		$url = 'https://www.classy.org/api1/recurring?' . $attrs . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Get all the details about a specific project. Projects are also referred to as 
	 * designations and terminology is sometimes interchanged on Classy.
	 *
	 * @since      1.2.2
	 * @param 	 string 	$pid 	The unique ID of the project/designation
	 */	
	public function project_info($pid) {
		$url = 'https://www.classy.org/api1/project-info?pid=' . $pid . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}

	/**
	 * Retrieve ticket details for a specific campaign/event
	 *
	 * @since      1.2.2
	 * @param 	 string 	$eid 	The unique ID of the campaign/event
	 */	
	public function tickets($eid) {
		$url = 'https://www.classy.org/api1/tickets?eid=' . $eid . '&token=' . $this->token . '&cid='. $this->cid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$output = json_decode($output);
		return $output;
	}
}