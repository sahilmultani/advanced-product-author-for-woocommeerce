(function ($) {
	'use strict';

	// js for plugin help tip
	$( 'span.wpa_tooltip_icon' ).click( function( event ) {
		event.preventDefault();
		$( this ).next( '.wpa-woocommerce-help-tip' ).toggle();
	} );

	$('#wpa_select_users_list').chosen();
	$('#wpa_select_user_role_list').chosen();
	$('#wpa_select_filter_users').chosen();

	$(document).on('click', '#save_genral_setting', function () {
		wpa_save_genral_settings();
	});

	function wpa_save_genral_settings() {
		let wpa_settings_enable_disable;
		if ($('#wpa_settings_enable_disable').prop('checked') === true) {
			wpa_settings_enable_disable = 'on';
		} else {
			wpa_settings_enable_disable = 'off';
		}

		let wpa_select_users_list_arr = [];
		let wpa_select_users_list;
		wpa_select_users_list = $('#wpa_select_users_list').val();
        if ('' !== wpa_select_users_list) {
            wpa_select_users_list_arr = wpa_select_users_list;
        }

        let wpa_select_user_role_list_arr = [];
		let wpa_select_user_role_list;
		wpa_select_user_role_list = $('#wpa_select_user_role_list').val();
        if ('' !== wpa_select_user_role_list) {
            wpa_select_user_role_list_arr = wpa_select_user_role_list;
        }

        $('.wpa-settings-main .wpa-loader').css('display', 'block');

		$.ajax({
			type: 'POST',
			url: coditional_vars.ajaxurl,
			data: {
				'action': 'wpa_save_product_author_genral_settings',
				'wpa_settings_enable_disable': wpa_settings_enable_disable,
				'wpa_select_users_list': wpa_select_users_list_arr,
				'wpa_select_user_role_list': wpa_select_user_role_list_arr,
			},
			success: function() {
				$('.wpa-settings-main .wpa-loader').css('display', 'none');
			}
		});
	}
})(jQuery);