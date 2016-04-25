<?php
/*
* Child Theme Settings
* Requires Genesis 1.8 or later
*
* This file registers all of this child theme's
* specific Theme Settings, accessible from
* Genesis > Child Theme Settings.
*
* @package WPS_Starter_Genesis_Child
* @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since 1.0
* @alter 1.1.2012
*
* Registers a new admin page, providing content and corresponding menu item
* for the Child Theme Settings page.
*
* @package WPS_Starter_Genesis_Child
* @subpackage Admin
*
* @since 1.0.0
*/

class Child_Theme_Settings extends Genesis_Admin_Boxes {
	function __construct() {
		// Specify a unique page ID.
		$page_id = 'child';
		// Set it as a child to genesis, and define the menu and page titles
		/* There is a lot going on here that is fairly well documented. 
			$page_id sets the admin url slug for the page. For this page, 
			the admin url will be: wp-admin/admin.php?page=child. 
			$menu_ops controls the creation of the submenu item. 
			The submenu array controls who is the parent (obviously, if you are creating a Genesis Child Theme,
			this will be set to 'genesis'). However, the Genesis Admin class does not limit you. 
			If I wanted an admin page to appear under Appearance, I could change 'parent' to themes.php,
			or if I wanted to add an admin page to Media, I would change the parent to upload.php, etc. */
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title' => 'Custom Child Theme Settings',
				'menu_title' => 'Custom Child Theme Settings',
				'capability' => 'edit_posts',
			)
		);
		// Set up page options. These are optional, so only uncomment if you want to change the defaults
		/*Next, we have the settings field, which I have set to a constant CHILD_SETTINGS_FIELD. 
		This really can be whatever you want it to be to refer to your settings (e.g., 'child-settings'). 
		Then we have the default settings that you would like to be created. 
		The settings field and the defaults will be used to create an options field so that essentially
		add_option ( 'child-settings' , $default_settings ); takes place later. 
		The default settings will also be used to reset theme options.
		In my child theme, I have no values set, so on Reset, everything will be erased.
		Finally, we have everything we need to create the admin page via, what I could coin as the creation call. 
		And as an added bonus, we can validate/sanitize our settings via the genesis sanitization class. 
		So we add the action, which is expecting a reference array action. */
		$page_ops = array(
		'screen_icon' => 'options-general',
		//	'save_button_text' => 'Save Settings',
		//	'reset_button_text' => 'Reset Settings',
		//	'save_notice_text' => 'Settings saved.',
		//	'reset_notice_text' => 'Settings reset.',
		);
		
		// Give it a unique settings field.
		// You'll access them from genesis_get_option( 'option_name', CHILD_SETTINGS_FIELD );
		$settings_field = 'CHILD_SETTINGS_FIELD';
		
		// Set the default values
		$default_settings = array(
		'facebook-link' => '',
		'twitter-link' => '',
		'youtube-link' => '',
		'gmail-link' => '',
		'pinterest-link' => '',
		'linkedin-link' => '',
		'rev_slider_post_1' => '',
		'rev_slider_post_2' => '',
		'rev_slider_post_3' => '',
		);
		
		// Create the Admin Page
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		// Initialize the Sanitization Filter
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );
	}
		
	/* 
	* Set up Sanitization Filters
	*
	* See /lib/classes/sanitization.php for all available filters.
	*
	* @since 1.0.0
	*
	*	Sanitation Filters options: 
	*	one_zero: Same as true-false
	*	no_html: Does not allow for any HTML
	*	safe_html: Removes unsafe HTML via wp_kses_post()
	*	requires_unfiltered_html: Keeps the option from being updated if the user lacks unfiltered_html capability */
	function sanitization_filters() {
		/*genesis_add_option_filter( 'no_html', $this->settings_field,
			array(
			'phone',
			'address',
		) );*/
		genesis_add_option_filter( 'no_html', $this->settings_field,
			array(
		'facebook-link',
		'twitter-link',
		'youtube-link',
		'gmail-link',
		'pinterest-link',
		'linkedin-link',
		'rev_slider_post_1',
		'rev_slider_post_2',
		'rev_slider_post_3'
		));
	}
	
		
	/**
	* Register metaboxes on Child Theme Settings page
	*
	* @since 1.0.0
	*
	* @see Child_Theme_Settings::contact_information() Callback for contact information
	*/
	function metaboxes() {
		//add_meta_box( 'contact-information', 'Contact Information', array( $this, 'contact_information' ), $this->pagehook, 'main', 'high' );
		add_meta_box( 'social-information', 'Social Information', array( $this, 'social_information' ), $this->pagehook, 'main', 'high' );
		add_meta_box( 'rev-slider-posts', 'Home Page Slider Post IDs', array( $this, 'rev_slider_posts' ), $this->pagehook, 'main', 'high' );
	}

	/**
	* Callback for Contact Information metabox
	*
	* @since 1.0.0
	*
	* @see Child_Theme_Settings::metaboxes()
	* This is what will go inside your metabox. It is fairly straightforward but there are a couple of functions that I'd like 
	* to point out: get_field_name, get_field_id, & get_field_value. These are built in functions that will take information 
	* provided in the construct method that will generate proper HTML names, ids, and values based on your $settings_field.
	*/
	function contact_information() {
		echo '<p>Phone:<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'phone' ) . '" id="' . $this->get_field_id( 'phone' ) . '" value="' . esc_attr( $this->get_field_value( 'phone' ) ) . '" size="50" />';
		echo '</p>';
		echo '<p>Address</p>';
		echo '<p><textarea name="' . $this->get_field_name( 'address' ) . '" cols="78" rows="8">' . esc_textarea( $this->get_field_value( 'address' ) ) . '</textarea></p>';
	}

	function social_information() {
		echo '<h4><em>Place your links into corresponding fields per social icon. This will be used into your Header &amp; Footer Social Icon links.</em></h4>';
		echo '<p>Facebook Link:<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'facebook-link' ) . '" id="' . $this->get_field_id( 'facebook-link' ) . '" value="' . esc_attr( $this->get_field_value( 'facebook-link' ) ) . '" size="50" />';
		echo '</p>';
		echo '<p>Twitter Link</p>';
		echo '<input type="text" name="' . $this->get_field_name( 'twitter-link' ) . '" id="' . $this->get_field_id( 'twitter-link' ) . '" value="' . esc_attr( $this->get_field_value( 'twitter-link' ) ) . '" size="50" />';
		echo '<p>Youtube Link</p>';
		echo '<input type="text" name="' . $this->get_field_name( 'youtube-link' ) . '" id="' . $this->get_field_id( 'youtube-link' ) . '" value="' . esc_attr( $this->get_field_value( 'youtube-link' ) ) . '" size="50" />';
		echo '<p>Gmail Link</p>';
		echo '<input type="text" name="' . $this->get_field_name( 'gmail-link' ) . '" id="' . $this->get_field_id( 'gmail-link' ) . '" value="' . esc_attr( $this->get_field_value( 'gmail-link' ) ) . '" size="50" />';
		echo '<p>Pinterest Link</p>';
		echo '<input type="text" name="' . $this->get_field_name( 'pinterest-link' ) . '" id="' . $this->get_field_id( 'pinterest-link' ) . '" value="' . esc_attr( $this->get_field_value( 'pinterest-link' ) ) . '" size="50" />';
		echo '<p>Linkedin Link</p>';
		echo '<input type="text" name="' . $this->get_field_name( 'linkedin-link' ) . '" id="' . $this->get_field_id( 'linkedin-link' ) . '" value="' . esc_attr( $this->get_field_value( 'linkedin-link' ) ) . '" size="50" />';
	}

	function rev_slider_posts() {
		echo '<h4><em>Please indicate the Post ID to be shown in your Homepage Slider</em></h4>';
		echo '<p>First Slider Item<br />';
		echo '<input type="number" name="' . $this->get_field_name( 'rev_slider_post_1' ) . '" id="' . $this->get_field_id( 'rev_slider_post_1' ) . '" value="' . esc_attr( $this->get_field_value( 'rev_slider_post_1' ) ) . '" size="50" />';
		echo '</p>';
		echo '<p>Second Slider Item</p>';
		echo '<input type="number" name="' . $this->get_field_name( 'rev_slider_post_2' ) . '" id="' . $this->get_field_id( 'rev_slider_post_2' ) . '" value="' . esc_attr( $this->get_field_value( 'rev_slider_post_2' ) ) . '" size="50" />';
		echo '<p>Third Slider Item</p>';
		echo '<input type="number" name="' . $this->get_field_name( 'rev_slider_post_3' ) . '" id="' . $this->get_field_id( 'rev_slider_post_3' ) . '" value="' . esc_attr( $this->get_field_value( 'rev_slider_post_3' ) ) . '" size="50" />';
	}

	/**
	* Register contextual help on Child Theme Settings page
	*
	* @since 1.0.0
	*
	*/
	function help( ) {
		global $my_admin_page;
		$screen = get_current_screen();
		if ( $screen->id != $this->pagehook )
		return;
		
		$tab1_help =
		'<h3>' . __( 'H3 Heading' , 'genesis' ) . '</h3>' .
		'<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in odio lacus. Fusce lacinia viverra facilisis. Nunc urna lorem, tempus in sollicitudin ac, fringilla non lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque lacinia, arcu ut porta laoreet, elit justo volutpat augue, commodo condimentum neque sapien a tellus. Fusce tempus elit sodales dui vehicula tempus. Aliquam lobortis laoreet tortor, facilisis blandit sem viverra at. Ut iaculis, metus ac faucibus aliquam, diam tortor commodo felis, sed fermentum velit nunc ac arcu. Ut in libero ante.' , 'genesis' ) . '</p>';

		$tab2_help =
		'<h3>' . __( 'H3 Heading' , 'genesis' ) . '</h3>' .
		'<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in odio lacus. Fusce lacinia viverra facilisis. Nunc urna lorem, tempus in sollicitudin ac, fringilla non lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque lacinia, arcu ut porta laoreet, elit justo volutpat augue, commodo condimentum neque sapien a tellus. Fusce tempus elit sodales dui vehicula tempus. Aliquam lobortis laoreet tortor, facilisis blandit sem viverra at. Ut iaculis, metus ac faucibus aliquam, diam tortor commodo felis, sed fermentum velit nunc ac arcu. Ut in libero ante.' , 'genesis' ) . '</p>';

		$tab3_help =
		'<h3>' . __( 'H3 Heading' , 'genesis' ) . '</h3>' .
		'<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in odio lacus. Fusce lacinia viverra facilisis. Nunc urna lorem, tempus in sollicitudin ac, fringilla non lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque lacinia, arcu ut porta laoreet, elit justo volutpat augue, commodo condimentum neque sapien a tellus. Fusce tempus elit sodales dui vehicula tempus. Aliquam lobortis laoreet tortor, facilisis blandit sem viverra at. Ut iaculis, metus ac faucibus aliquam, diam tortor commodo felis, sed fermentum velit nunc ac arcu. Ut in libero ante.' , 'genesis' ) . '</p>' .
		'<h4>' . __( 'H4 Heading' , 'genesis' ) . '</h4>' .
		'<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in odio lacus. Fusce lacinia viverra facilisis. Nunc urna lorem, tempus in sollicitudin ac, fringilla non lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque lacinia, arcu ut porta laoreet, elit justo volutpat augue, commodo condimentum neque sapien a tellus. Fusce tempus elit sodales dui vehicula tempus. Aliquam lobortis laoreet tortor, facilisis blandit sem viverra at. Ut iaculis, metus ac faucibus aliquam, diam tortor commodo felis, sed fermentum velit nunc ac arcu. Ut in libero ante.' , 'genesis' ) . '</p>';

		$screen->add_help_tab(
			array(
			'id'	=> $this->pagehook . '-tab1',
			'title'	=> __( 'Tab 1' , 'genesis' ),
			'content'	=> $tab1_help,
		) );
		$screen->add_help_tab(
		array(
			'id'	=> $this->pagehook . '-tab2',
			'title'	=> __( 'Tab 2' , 'genesis' ),
			'content'	=> $tab2_help,
		) );
		$screen->add_help_tab(
		array(
			'id'	=> $this->pagehook . '-tab3',
			'title'	=> __( 'Tab 3' , 'genesis' ),
			'content'	=> $tab3_help,
		) );

	// Add Genesis Sidebar
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', 'genesis' ) . '</strong></p>'.
			'<p><a href="' . __( 'http://www.studiopress.com/support', 'genesis' ) . '" target="_blank" title="' . __( 'Support Forums', 'genesis' ) . '">' . __( 'Support Forums', 'genesis' ) . '</a></p>'.
			'<p><a href="' . __( 'http://www.studiopress.com/tutorials', 'genesis' ) . '" target="_blank" title="' . __( 'Genesis Tutorials', 'genesis' ) . '">' . __( 'Genesis Tutorials', 'genesis' ) . '</a></p>'.
			'<p><a href="' . __( 'http://dev.studiopress.com/', 'genesis' ) . '" target="_blank" title="' . __( 'Genesis Developer Docs', 'genesis' ) . '">' . __( 'Genesis Developer Docs', 'genesis' ) . '</a></p>'
		);
	}

} /*End class Child_Theme_Settings*/

/**
* Add the Theme Settings Page
*
* @since 1.0.0
*/
function wps_add_child_theme_settings() {
	global $_child_theme_settings;
	$_child_theme_settings = new Child_Theme_Settings;
}
add_action('admin_menu', 'wps_add_child_theme_settings', 5 );
?>