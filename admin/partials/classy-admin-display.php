<?php

/**
 * Provide a dashboard view for the plugin
 *
 * @link       http://mediacause.org
 * @since      1.2
 *
 * @package    Classy
 * @subpackage Classy/admin/partials
 */

$classy = new Classy_API(); 
$account = $classy->account_info();
$activities = $classy->account_activity();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1><span class="dashicons dashicons-calendar-alt"></span> Classy API</h1>
<div class="wrap">
	<div class="classy-form postbox">
		<div class="inside">
			<form action="" method="post" id="update-classy" validate>
				<div class="form-group">
					<label for="token">Token</label>
					<input type="text" name="token" id="token" placeholder="Token" value="<?php echo get_option( 'classy_token' ); ?>" required>
				</div>
				<div class="form-group">
					<label for="cid">CID</label>
					<input type="text" name="cid" id="cid" placeholder="CID" value="<?php echo get_option( 'classy_cid' ); ?>" required>
				</div>
				<input type="submit" name="update" value="Save" class="button button-primary pull-right">
			</form>
			<hr>
			<?php if($account->status_code == "SUCCESS"): ?>
			<p>Organization: <?php echo $account->name; ?></p>
			<p>Total Raised: $<?php echo $account->total_raised; ?></p>
			<p>Total Events: <?php echo $account->total_active_events; ?></p>
			<p>Total Supporters: <?php echo $account->total_supporters; ?></p>
			<p>Total Teams: <?php echo $account->total_fund_teams; ?></p>
			<?php endif; ?>
			<?php  ?>
		</div>
	</div>
</div>
<?php if($activities->status_code == "SUCCESS"): ?>
<h1><span class="dashicons dashicons-update"></span> Activity Stream</h1>
<div class="wrap">
	<div class="classy-form postbox">
		<div class="inside">
			<ul>
				<?php foreach ($activities->activity as $activity) {
					echo '<li>'. $activity->activity_string . '</li>';
				}
				?>
			</ul>
		</div>
	</div>
</div>
<?php endif; ?>