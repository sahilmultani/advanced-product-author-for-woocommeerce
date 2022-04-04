<?php 
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpa-settings-main">
	<h2><?php esc_html_e('Advanced Product Author for WooCommerce', 'advanced-product-author-for-woocommerce'); ?></h2>
	<div class="wpa_settings_page_nav">
	    <?php 
	    echo wp_kses_post( WPA_Woo_Product_Author_Admin::wpa_settings_page_tabs());
	    ?>
	</div>

	<?php
	$wpa_settings_enable_disable = get_option( 'wpa_settings_enable_disable' );
	$wpa_select_users_list = get_option( 'wpa_select_users_list' );
	$wpa_select_user_role_list = get_option( 'wpa_select_user_role_list' );
	?>
	<div class="wpa_settings_inner">
		<table class="form-table">
			<tr>
				<th scope="row">
					<label class="wpa_leble_setting" for="wpa_settings_enable_disable"><?php esc_html_e( 'Status', 'advanced-product-author-for-woocommerce' ); ?>
					</label>
				</th>
				<td>
					<label class="wpa_toggle_switch">
						<input type="checkbox" value="on" id="wpa_settings_enable_disable" class="wpa_settings_enable_disable" <?php checked( $wpa_settings_enable_disable, 'on' ); ?>>
						<span class="wpa_toggle_btn"></span>
					</label>
					<span class="wpa_tooltip_icon"></span>
					<div class="wpa-woocommerce-help-tip">
						<p class="wpa_tooltip_desc description">
							<?php esc_html_e( 'Enable or Disable author selection settings using this button.', 'advanced-product-author-for-woocommerce' ); ?>
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label class="wpa_leble_setting" for="wpa_select_users_list"><?php esc_html_e( 'Select Users', 'advanced-product-author-for-woocommerce' ); ?>
					</label>
				</th>
				<td>
					<select name="wpa_select_users_list[]" id="wpa_select_users_list" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select a User', 'advanced-product-author-for-woocommerce' ); ?>">
						<?php 
						// get user list
						$get_all_users = get_users();
						$wpa_select_users_list = (is_array($wpa_select_users_list)) ? $wpa_select_users_list : [$wpa_select_users_list];
						if ( isset($get_all_users) && is_array( $get_all_users ) && ! empty( $get_all_users ) ) {
							foreach ( $get_all_users as $get_all_user ) {
								?>
								<option value="<?php esc_attr_e( $get_all_user->data->user_login, 'advanced-product-author-for-woocommerce' ); ?>" <?php echo in_array( $get_all_user->data->user_login, $wpa_select_users_list, true ) ? 'selected' : ''; ?>><?php esc_html_e( $get_all_user->data->display_name, 'advanced-product-author-for-woocommerce' ); ?></option>
								<?php
							}
						}
						?>
					</select>
					<span class="wpa_tooltip_icon"></span>
					<div class="wpa-woocommerce-help-tip">
						<p class="wpa_tooltip_desc description">
							<?php esc_html_e( 'Select users if you want current user based author selection settings.', 'advanced-product-author-for-woocommerce' ); ?>
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label class="wpa_leble_setting" for="wpa_select_user_role_list"><?php esc_html_e( 'Select User Roles', 'advanced-product-author-for-woocommerce' ); ?></label>
				</th>
				<td>
					<select name="wpa_select_user_role_list[]" id="wpa_select_user_role_list" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select a User Role', 'advanced-product-author-for-woocommerce' ); ?>">
						<?php 
						// get user role list
						global $wp_roles;
						$wpa_select_user_role_list = (is_array($wpa_select_user_role_list)) ? $wpa_select_user_role_list : [$wpa_select_user_role_list];
						if ( isset($wp_roles->roles) && is_array( $wp_roles->roles ) && ! empty( $wp_roles->roles ) ) {
							foreach ( $wp_roles->roles as $user_role_key => $get_all_role ) {
								?>
		                        <option value="<?php echo esc_attr( $user_role_key ); ?>" <?php echo in_array( $user_role_key, $wpa_select_user_role_list, true ) ? 'selected' : ''; ?>>
		                        	<?php echo esc_html( $get_all_role['name'] ); ?></option>
								<?php
							}
						} 
						?>
					</select>
					<span class="wpa_tooltip_icon"></span>
					<div class="wpa-woocommerce-help-tip">
						<p class="wpa_tooltip_desc description">
							<?php esc_html_e( 'Select user roles if you want current user role based author selection settings.', 'advanced-product-author-for-woocommerce' ); ?>
						</p>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="wpa-save-button bottom-save-button">
		<input type="button" name="save_genral_setting" id="save_genral_setting" class="button button-primary button-large" value="<?php esc_attr_e( 'Save Changes', 'advanced-product-author-for-woocommerce' ); ?>">
		<div class="wpa-loader"></div>
	</div>
</div>
<?php