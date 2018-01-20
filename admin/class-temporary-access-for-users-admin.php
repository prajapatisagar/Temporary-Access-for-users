<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://about.me/sagarprajapati48
 * @since      1.0.0
 *
 * @package    Temporary_Access_For_Users
 * @subpackage Temporary_Access_For_Users/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Temporary_Access_For_Users
 * @subpackage Temporary_Access_For_Users/admin
 * @author     Sagar Prajapati <sagarprajapati48@gmail.com>
 */
class Temporary_Access_For_Users_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Temporary_Access_For_Users_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Temporary_Access_For_Users_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/temporary-access-for-users-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui-datepicker' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Temporary_Access_For_Users_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Temporary_Access_For_Users_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/temporary-access-for-users-admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ), $this->version, false );

	}
	
	/**
	 * Add the field to user profiles
	 *
	 * @since 1.0.0
	 * @param object $user
	 */
	public function user_profile_data( $user ) {

		// Only show this option to users who can delete other users
		if ( !current_user_can( 'edit_users' ) )
			return;
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<label for="tau_expire_after"><?php _e(' Expire After', 'tau_temporary_login' ); ?></label>
					</th>
					<td>
						<input type="date" id="tau_expire_after" name="tau_expire_after" value="<?php echo get_the_author_meta( 'tau_expire_after', $user->ID ); ?>" class="tau_expire_after" />
						<span class="description"><?php _e( 'After this time user can not login for this account.' , 'tau_temporary_login' ); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="tau_disable_user"><?php _e(' Disable this Account', 'tau_temporary_login' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="tau_disable_user" id="tau_disable_user" value="1" <?php checked( 1, get_the_author_meta( 'tau_disable_user', $user->ID ) ); ?> />
						<span class="description"><?php _e( 'Checked this for disable this user Account' , 'tau_temporary_login' ); ?></span>
					</td>
				</tr>
			<tbody>
		</table>
		<?php
	}

	/**
	 * Saves the custom field to user meta
	 *
	 * @since 1.0.0
	 * @param int $user_id
	 */
	public function user_profile_data_save( $user_id ) {

		// Only worry about saving this field if the user has access
		if ( !current_user_can( 'edit_users' ) )
			return;

		if ( !isset( $_POST['tau_disable_user'] ) ) {
			$disabled = 0;
		} else {
			$disabled = $_POST['tau_disable_user'];
		}
		
		if ( !isset( $_POST['tau_expire_after'] ) ) {
			$tau_expire_after = 0;
		} else {
			$tau_expire_after = $_POST['tau_expire_after'];
		}
	 
		update_user_meta( $user_id, 'tau_disable_user', $disabled );
		update_user_meta( $user_id, 'tau_expire_after', $tau_expire_after );
	}

	/**
	 * After login check user is eligible for access or not
	 *
	 * @since 1.0.0
	 * @param string $user_access
	 * @param object $user
	 */
	public function user_access( $user_access, $user = null ) {

		if ( !$user ) {
			$user = get_user_by('login', $user_access);
		}
		if ( !$user ) {
			return;
		}
		// Get user meta
		$disabled = get_user_meta( $user->ID, 'tau_disable_user', true );
		$tau_expire_after = get_user_meta( $user->ID, 'tau_expire_after', true );
		// check the time frame
		if ( $disabled == '1' || ( strtotime($tau_expire_after) <= strtotime( date( 'd-m-Y' ) ) && !empty($tau_expire_after) ) ) {
			// Clear cookies for user
			wp_clear_auth_cookie();

			// redirect to login URL
			$login_url = site_url( 'wp-login.php', 'login' );
			$login_url = add_query_arg( 'disabled', '1', $login_url );
			wp_redirect( $login_url );
			exit;
		}
	}

	/**
	 * Show a notice to users who try to login and are disabled
	 *
	 * @since 1.0.0
	 * @param string $message
	 * @return string
	 */
	public function user_access_message( $message ) {

		// Display the message if user is not aligible for login
		if ( isset( $_GET['disabled'] ) && $_GET['disabled'] == 1 ) 
			$message =  '<div id="login_error">' . apply_filters( 'tau_temporary_login_notice', __( 'This Account is disable by Administrator', 'tau_temporary_login' ) ) . '</div>';

		return $message;
	}

	/**
	 * Add custom disabled column to users list
	 *
	 * @since 1.0.0
	 * @param array $defaults
	 * @return array
	 */
	public function manage_users_columns( $defaults ) {

		$defaults['tau_user_disabled'] = __( 'Disabled', 'tau_temporary_login' );
		return $defaults;
	}

	/**
	 * Set content of disabled users column
	 *
	 * @since 1.0.0
	 * @param empty $empty
	 * @param string $column_name
	 * @param int $user_ID
	 * @return string
	 */
	public function manage_users_column_content( $empty, $column_name, $user_ID ) {

		if ( $column_name == 'tau_user_disabled' ) {
			if ( get_the_author_meta( 'tau_disable_user', $user_ID )	== 1 ) {
				return __( 'Disabled', 'tau_temporary_login' );
			}
		}
	}

}
