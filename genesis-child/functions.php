<?php
//** Start the engine
require_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Workshop Theme - Genesis Child' );
define( 'CHILD_THEME_URL', 'http://localhost/training' );

//* Add Viewport meta tag for mobile browsers
add_action( 'genesis_meta', 'add_viewport_meta_tag' );
function add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

//* ------------- ADD PARENT THEME SUPPORT ------------- *//
//* --- Add HTML5 markup structure from Genesis*/
add_theme_support( 'html5' );
//* --- Add HTML5 responsive recognition*/
add_theme_support( 'genesis-responsive-viewport' );
//* --- Add support for custom header
add_theme_support( 'genesis-admin-menu' );
//* --- Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 2 );
//* --- Add support for custom header 
add_theme_support( 'custom-header',array('header-selector' => '.site-title a','header-text' => false,'height' => 60,'width' => 220,) );

//* --- Add suport for structural wraps
add_theme_support( 'genesis-structural-wraps', array('header','nav','subnav','site-inner','footer-widgets','footer') );
//* --- Add Scripts
add_action( 'wp_enqueue_scripts', 'my_child_theme_scripts' );
function my_child_theme_scripts() {
    wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'bootstrap-theme-css', get_stylesheet_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css' );
    wp_enqueue_style( 'fontawesome-css', get_stylesheet_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css' );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_script( 'responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/assets/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'bootstrap-js', get_bloginfo( 'stylesheet_directory' ) . '/assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'jquerysticky-js', get_bloginfo( 'stylesheet_directory' ) . '/assets/js/jquery.sticky.js', array( 'jquery' ), '1.0.0' );
}

//* --- Add Sticky Widget
add_action( 'widgets_init', 'sticky_widget' );
function sticky_widget() {
    register_sidebar( array(
        'name' => __( 'Sticky Widget', 'genesis' ),
        'id' => 'sticky-widget',
        'description' => __( 'Place one widget that will stick upon long page content.', 'genesis' ),
        'before_widget' => '<div id="%1$s" class="sticky-widget widget %2$s"><div class="widget-wrap">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h4 class="widget-title widgettitle">',
		'after_title'   => '</h4>',
    ) );
}
add_action('genesis_after_sidebar_widget_area','add_sticky_widget');
function add_sticky_widget() { 
	if (is_active_sidebar('sticky-widget')) { 
		dynamic_sidebar('sticky-widget');
	}
}

//* ------------- OUR CUSTOM FUNCTIONS -------------*//
//* --- Settings for Admin Featured Article
require_once( get_stylesheet_directory().'/custom/admin/admin-edit-post.php');
//* --- Additional Child Theme Settings
require_once( get_stylesheet_directory().'/custom/admin/child-theme-settings.php');
//* --- Header Content
require_once( get_stylesheet_directory().'/custom/layout/header-layout.php');

//* --- Add Title before Main Loop
add_action('genesis_before_loop','main_loop_title',15);
function main_loop_title() {
	if (is_home()) {
		echo "<h3>Articles</h3>";
	} elseif (is_single()) {

	} elseif (is_archive()) {
		if (is_date()) {

		} elseif (is_year()) {

		} elseif (is_author()) {
			global $authordata;
			$display_name = get_the_author_meta('display_name');
			echo "<h3>Articles written by $display_name</h3>";
		} elseif (is_tag()) {
			$catname = strip_tags(single_cat_title('',false));
			echo "<h3>Articles with $catname tags</h3>";
		}
			else {
			$catname = strip_tags(single_cat_title('',false));
			echo "<h3>$catname Articles</h3>";
		}
	}
}

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'author_box_gravatar_size' );
function author_box_gravatar_size( $size ) {
	return '150';
}

//* --- Conditional Offsets for Posts
//add_action('pre_get_posts','pre_get_post_category_page');
function pre_get_post_category_page( $query ) {
    if ( !is_admin() && $query->is_main_query() ) {
	    if ($query->is_archive() && !is_date()) {
        	$query->set( 'offset','6' );
	    }
		$query->set( 'offset','6' ); 
		return $query;   
    }
    return $query;
}
//* --- Pre get post for Archive Dates/Months/Year
add_action('pre_get_posts','pre_get_post_archive_date');
function pre_get_post_archive_date( $query ) {
    if ($query->is_date() && $query->is_main_query()) {
    	if ($query->is_month()) {
    		$query->query_vars['offset'] = 0;
		//$query->query_vars['showposts'] = 20;
			$query->set('posts_per_archive_page', -1);
			return $query;  
		} elseif ($query->is_year()) {
			$query->query_vars['offset'] = 0;
			$query->set('posts_per_archive_page', 50);
			return $query;  
		}
		
    } elseif ($query->is_category && $query->is_main_query()) {
    	$query->set( 'offset','6' );
    	return $query;  
    }
}

//* --- Pre get post for Archive Author
add_action('pre_get_posts','pre_get_post_archive_author');
function pre_get_post_archive_author( $query ) {
    if ($query->is_author() && $query->is_main_query()) {
		$query->query_vars['offset'] = 0;
		$query->set('posts_per_archive_page', 12);
		}
	return $query;  
}
//* ------------- REPOSITIONS ------------- *//
//* --- Reposition Post Info
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_footer', 'genesis_post_info', 9 );

//* --- Reposition Widget Areas
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_footer', 'genesis_footer_widget_areas',9 );


//* ------------- GENESIS FILTERS ------------- *//
//* --- Customize post_info
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
	$post_info = 'By [post_author_posts_link] &#124; [post_date] [post_comments zero="0 Comment" one="1 Comment" more="% Comments"]';
	return $post_info;
}
//* --- Customize Post Meta
add_filter( 'genesis_post_meta', 'sp_post_meta_filter' );
function sp_post_meta_filter($post_meta) {
	$post_meta = '[post_categories before="Category : "] [post_tags before="Tagged: "]';
	return $post_meta;
}

//* --- Remove Credits text from Footer
add_filter('genesis_footer_creds_text','__return_false'); 
function copyright_text(){
	return '<div class="copyright-text">© 2016 Emerson Tool Company. All Rights Reserved.Privacy Policy| Terms of Use| Site Map</div>';
}

//* --- Change Genesis Search Button Text
add_filter('genesis_search_text','search_form_text'); 
function search_form_text($label){
	return "Search...";
}

//* Customize the author box title
add_filter( 'genesis_author_box_title', 'custom_author_box_title' );
function custom_author_box_title() {
	$display_name = get_the_author_meta('display_name');
	return $display_name;
}

//* --- Disable Suferfish JS for Menu Support
add_filter( 'genesis_superfish_enabled', '__return_false' );


//* --- Change Text for no posts
add_filter( 'genesis_noposts_text', 'no_posts' );
function no_posts() {
	if (is_search()) {
		return "No search results.";
	} 
	if (is_archive()) {
		return false;
	}
}
//* ------------- PLUGINS ------------- *//

//* ------------- LOAD MORE CUSTOMIZATIONS ------------- *//

//* --- Add id="main-content" attributes to <main> element for Load More Container
add_filter( 'genesis_attr_content', 'custom_attributes_content' );
function custom_attributes_content( $attributes ) {
  $attributes['id'] = 'main-content';
  return $attributes;
}

//* --- Add Jetpack's Loadmore Featured
add_action( 'after_setup_theme', 'custom_infinite_scroll' );
function custom_infinite_scroll() {
  add_theme_support( 'infinite-scroll', array(
      'container'  => 'main-content',
      'footer'     => false,
      'type'       => 'click',
      'render'    => 'main_query_loop',
      'posts_per_page' => 10
  ) );
}
//* --- Conditional Load More Loop
function main_query_loop() {
	if (is_archive() && !is_category()) {
		//* We will remove the Standard Loop when a page is Archive - Date, Year, Month
		remove_action('genesis_loop','genesis_do_loop');
		//* We will add a Genesis Custom Loop to display
		add_action('genesis_loop','archive_custom_loop');
		//* Call the loop
		do_action('genesis_loop');
	} else {
		//* Call the loop
		do_action('genesis_loop');
	}
}

//* --- Remove Post Meta to all Pages, Post Meta only on Single Post Page
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );


//* --- Our Genesis Custom Loop, use -> archive_custom_loop
function archive_custom_loop() {
	add_action( 'genesis_entry_header', 'genesis_post_info' );
	add_filter( 'genesis_post_info', 'archive_post_meta' );
	function archive_post_meta($post_info) {
		$post_info = '[post_date]';
		return $post_info;
	}

	if ( have_posts() ) :
		do_action( 'genesis_before_while' );
		while ( have_posts() ) : the_post();
			do_action( 'genesis_before_entry' );
			printf( '<article %s>', genesis_attr( 'entry' ) );
				do_action( 'genesis_entry_header' );
				do_action( 'genesis_before_entry_content' );
				do_action( 'genesis_after_entry_content' );
			echo '</article>';
			do_action( 'genesis_after_entry' );
		endwhile; //* end of one post
		do_action( 'genesis_after_endwhile' );
	else : //* if no posts exist
		do_action( 'genesis_loop_else' );
	endif; //* end loop
}

//* --- Remove Post Pagination of Genesis from the Main Query *Related to Jetpack Loadmore Function
add_action('init','loop_alter');
function loop_alter(){
	remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

}

//* -------------- Page Speed Enhancements ------------ *//
//* --- Remove Query String from Static Resources
function remove_cssjs_ver( $src ) {
	if( strpos( $src, '?ver=' ) )
	$src = remove_query_arg( 'ver', $src );
	return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );


//* ------------- Add Custom Title Filter ------------- *//	
add_filter( 'wp_title', 'custom_title_filter', 10, 1 );	
function custom_title_filter($title) {
	if (is_single() || is_page() || is_category() || is_archive()) {
		$title = $title . " | Workshop - The World's Dirtiest Blog";
		return $title;
	} else if ( 'posts' == get_option( 'show_on_front' ) ) { 
		$title = $title;
		return $title;
	}
}

//* ------------- Register the shortcode to wrap Video Embed Content ------------- *//
function resp_video( $atts ) {
    extract( shortcode_atts( array (
        'embedlink' => ''
    ), $atts ) );
    return '<div class="videoWrapper"><iframe src="' . $embedlink . '" height="240" width="320" allowfullscreen="" frameborder="0"></iframe></div>';
}
add_shortcode ('responsive-video', 'resp_video' );


//* ------------- REVOLUTION SLIDER ------------- *//

//* --- Added Filter relative to our Child Theme setting values. These will override slider's Specific Post List
add_filter('revslider_set_posts_list','myCustomRevSlider'); 
function myCustomRevSlider($slider_id) {
	$rev_slider_post_1 = genesis_get_option( 'rev_slider_post_1', 'CHILD_SETTINGS_FIELD' );
	$rev_slider_post_2 = genesis_get_option( 'rev_slider_post_2', 'CHILD_SETTINGS_FIELD' );
	$rev_slider_post_3 = genesis_get_option( 'rev_slider_post_3', 'CHILD_SETTINGS_FIELD' );
	$slider_id = array ();
	$slider_id[0] = $rev_slider_post_1;
	$slider_id[1] = $rev_slider_post_2;
	$slider_id[2] = $rev_slider_post_3;
 	return $slider_id;
}