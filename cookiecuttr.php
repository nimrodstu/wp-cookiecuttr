<?php
/**
 * Plugin Name: wp cookiecuttr by nimrodstu
 * Description: adds cookiecuttr code and scripts 
 * Version: 2.0
 * Author: nimrodstu
 * Author URI: http://nimrodstu.com
 * License: A "Slug" license name e.g. GPL2
 */


/**
 * Proper way to enqueue scripts and styles
 */
function cookiecutter_scripts() {
	wp_register_script( 'cookie-js', plugins_url('/scripts/jquery.cookie.js', __FILE__), array('jquery'),'',true  );
	wp_register_script( 'cookiecutter-jquery', plugins_url('/scripts/jquery.cookiecuttr.js', __FILE__), array('jquery'),'',true  );
	//wp_register_script( 'cookiecutter-js', plugins_url('/cookiecuttr.js', __FILE__), array('jquery'),'',true  );
    wp_register_style( 'cookiecuttr-css', plugins_url('/css/cookiecuttr.css', __FILE__),'','', 'screen' );
 
    wp_enqueue_script( 'cookie-js' );
    wp_enqueue_script( 'cookiecutter-jquery' );
	//wp_enqueue_script( 'cookiecutter-js' );
    wp_enqueue_style( 'cookiecuttr-css' );
}
add_action( 'wp_enqueue_scripts', 'cookiecutter_scripts' );


//new menu item
function my_plugin_menu() {
	add_options_page( 'WP CookieCuttr Options', 'WP CookieCuttr', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}

add_action( 'admin_init', 'my_plugin_settings' );

//set options
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} ?>
	<div class="wrap">
	<h2>CookieCuttr Options</h2>
		<form method="post" action="options.php">
		    <?php settings_fields( 'my-plugin-settings-group' ); ?>
		    <?php do_settings_sections( 'my-plugin-settings-group' ); ?>
		    <?php $options = get_option( 'cookie_policy_link' ); ?>
		    <table class="form-table">
		    	<tr valign="top">
		    		<th scope="row">Cookie Page Link</th>
                    <td>
                        <select name="cookie_policy_link[page_id]">
                            <?php
                            if( $pages = get_pages() ){
                                foreach( $pages as $page ){
                                    echo '<option value="' . $page->ID . '" ' . selected( $page->ID, $options['page_id'] ) . '>' . $page->post_title . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
		        <tr valign="top">
			        <th scope="row">Cookie accept button text</th>
			        <td><input type="text" name="cookie_accept_button_text" value="<?php echo esc_attr( get_option('cookie_accept_button_text') ); ?>" /></td>
		        </tr>
		        <tr valign="top">
			        <th scope="row">Cookie message</th>
			        <td><input type="text" name="cookie_message" value="<?php echo esc_attr( get_option('cookie_message') ); ?>" /></td>
		        </tr>
		        <tr valign="top">
			        <th scope="row">Cookie what are link text</th>
			        <td><input type="text" name="cookie_what_are_link_text" value="<?php echo esc_attr( get_option('cookie_what_are_link_text') ); ?>" /></td>
		        </tr>
		    </table>
		    <?php submit_button(); ?>
		</form>
	</div>
<?php }
add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_settings() {
	register_setting( 'my-plugin-settings-group', 'cookie_policy_link' );
	register_setting( 'my-plugin-settings-group', 'cookie_accept_button_text' );
	register_setting( 'my-plugin-settings-group', 'cookie_message' );
	register_setting( 'my-plugin-settings-group', 'cookie_what_are_link_text' );
}

function cookiecutter_opts() {
	$page = get_option( 'cookie_policy_link' );
//load javascript to pages head ?>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery.cookieCuttr({
		    cookieAnalytics: false,
		    cookieAcceptButtonText: '<?php echo esc_attr( get_option('cookie_accept_button_text') ); ?>',
		    cookieMessage: "<?php echo esc_attr( get_option('cookie_message') ); ?> <a href='<?php echo get_permalink($page['page_id']); ?>'><?php echo esc_attr( get_option('cookie_what_are_link_text') ); ?></a> "
			});
		}); 
	</script>
<?php }
add_action('wp_head', 'cookiecutter_opts');