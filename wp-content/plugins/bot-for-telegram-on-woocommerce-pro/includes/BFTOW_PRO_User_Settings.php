<?php
new BFTOW_PRO_User_Settings;

class BFTOW_PRO_User_Settings
{


    function __construct()
    {
        add_action('show_user_profile', [$this, 'extra_profile_fields']);
        add_action('edit_user_profile', [$this, 'extra_profile_fields']);

        add_action('personal_options_update', [$this, 'update_profile']);
        add_action('edit_user_profile_update', [$this, 'update_profile']);
    }

    function extra_profile_fields($user)
    {
        $is_user_blocked = get_user_meta($user->ID, 'bftow_user_blocked', true);
        ?>
		<h3><?php esc_html_e('Telegram Bot Settings', 'bot-for-telegram-on-woocommerce-pro'); ?></h3>

		<table class="form-table">
			<tr>
				<th>
					<label for="block-user"><?php esc_html_e('Block user', 'bot-for-telegram-on-woocommerce-pro'); ?></label>
				</th>
				<td><input type="checkbox" name="bftow_user_blocked"
                        <?php echo !empty($is_user_blocked) ? 'checked' : ''; ?>></td>
			</tr>
		</table>
        <?php
    }

    function update_profile($user_id)
    {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }

        if ( ! empty( $_POST['bftow_user_blocked'] ) ) {
            update_user_meta( $user_id, 'bftow_user_blocked', true );
        }
        else {
            update_user_meta( $user_id, 'bftow_user_blocked', '' );
        }
    }
}