<?php

/**
 * Provide a dashboard view for the plugin
 *
 * @link       http://mediacause.org
 * @since      1.0.0
 *
 * @package    Classy
 * @subpackage Classy/admin/partials
 */
global $error;
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
			<p>Organization: <?php echo get_option('classy_org_name') ? get_option('classy_org_name') : 'None'; ?></p>
		</div>
	</div>
</div>